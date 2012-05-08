<?php
/*
    Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * This is a basic model, all models are inherited from this and it contains 
 * many basic methods for both - virtual and DB models.
 */

class AbstractBasicModel {

	var $package="Abstract";
	var $table = null;
	
	var $buttonsSet;
	var $attrOrder=0;
	var $name;
	var $nameShort;
	var $messages=array("errors" => array(), "warnings" => array());
	var $propertiesModelName = false;
	var $loadParams = array();
	var $uploadedFiles = array();
	var $databaseValues = array();
	var $languageSupport = null;
	var $predefinedValues = array();

	public $indexes = array();	// TODO: change to private
	
	static public $propertiesDefinitionCache = false;	// property cache - not to load each when creating the new object
	static public $propertiesOptionsCache = false;		// properties options cache - not to load each when creating the new object
	
    function __construct($id = false) {
    	
    	$this->name = get_class($this);
    	$this->nameShort = str_replace('model', '', str_replace(strtolower($this->package), '', strtolower(get_class($this))));
		$this->attributesDefinition();
		$this->propertiesDefinition();
		$this->cleanAttributes();
		
	}

	public function getName() {
		return $this->name;
	}
	
	/**
	 * Save data to some object, can be overwritten, but not has to be.
	 */
	public function Save() {
		$this->handleUploadedFile();
	}

	protected function handleUploadedFile() {
    	foreach ($this->getMetaData() as $field => $meta) {
    		if ($meta->getType() == Form::FORM_UPLOAD_FILE) {
    			$newFileName = Utilities::concatPath($meta->getUploadDir(), Utilities::getUniqueFileName(Utilities::makeFileNameFormat($_FILES[$meta->getName()]['name']), $meta->getUploadDir()));
    			if (!move_uploaded_file($_FILES[$meta->getName()]['tmp_name'], $newFileName)) {
    				throw new Exception('Cannot move uploaded file into ' . $newFileName . '. Maybe wrong directory attributes?');
    			} else {
    				if (Utilities::fileIsImage($newFileName)) {
    					Utilities::resizeImageIfNeeded($newFileName, DEFAULT_UPLOAD_IMAGE_WIDTH, DEFAULT_UPLOAD_IMAGE_HEIGHT);
    				}
    				$this->uploadedFiles[$meta->getName()] = $newFileName;
    			}
    		}
    	}
	}
	
	/**
	 * 
	 * @return string package name
	 */
	public function getPackage() {
		return $this->package;
	}
	
	/**
	 * Attributes definition
	 * Should be overwritten.
	 */
    protected function attributesDefinition() {
    }
    
	/**
	 * Properties definition
	 * Could be overwritten.
	 */
	protected function propertiesDefinition() {
	}
	
	/**
	 * Makes some additional changes in attributes definition
	 */
	protected function cleanAttributes() {
		if (!is_array($allMeta = $this->getMetaData()))
			return;
		foreach ($allMeta as $meta) {
			// add lang to index definition if needed
			$index = $meta->getSqlIndex();
			if ($index && $index->type == ModelMetaIndex::UNIQUE_LANG) {
				if (isset($this->languageSupportAllowed) && $this->languageSupportAllowed || $meta->getExtendedTable()) {
					$index->columns[] = 'lang';
				}
				$meta->setSqlIndex(ModelMetaIndex::UNIQUE, $index->columns);
			}
		}
	}
	
	/**
	 * Returns properties model.
	 */
	public function getPropertiesModel() {
		if ($this->propertiesModelName) {
			return new $this->propertiesModelName();
		} 
		return false;
	}
	
	protected function loadPropertiesDefinition($propertiesDefinitionModelName, $propertiesOptionsModelName) {
		foreach ($this->getPropertiesDefinition($propertiesDefinitionModelName) as $property) {
			$metaData = AtributesFactory::create($property->prop_name)
				->setLabel($property->prop_label)
				->setDescription($property->prop_description)
				->setType($property->prop_type)
				->setKind($property->prop_kind)
				->setOptions($this->getPropertiesOptions($propertiesOptionsModelName, $property->id));
			$this->addPropertiesMetaData($metaData);
		}
	}
	
	protected function getPropertiesDefinition($propertiesDefinitionModelName) {
		if (self::$propertiesDefinitionCache == false) {
			$propertiesDefinitionModel = new $propertiesDefinitionModelName();
			// TODO: find all not needed creations of the Model objects and use Search method..
			self::$propertiesDefinitionCache = $propertiesDefinitionModel->Find($propertiesDefinitionModelName);
			if (!is_array(self::$propertiesDefinitionCache)) {
				self::$propertiesDefinitionCache = array();
			}
		}
		return self::$propertiesDefinitionCache;
	}
	
	protected function getPropertiesOptions($propertiesOptionsModelName, $propertyId) {
		if (self::$propertiesOptionsCache === false) {
			$propertiesOptionModel = new $propertiesOptionsModelName();
			$temp = $propertiesOptionModel->Find($propertiesOptionsModelName);
			self::$propertiesOptionsCache = array();
			if ($temp) {
				foreach ($temp as $option) {
					if (!isset(self::$propertiesOptionsCache[$option->property])) {
						self::$propertiesOptionsCache[$option->property] = array();
					}
					self::$propertiesOptionsCache[$option->property][] = array(
						'id' => $option->id,
						'value' => $option->title,
					);
				}
			}
		}
		
		return (isset(self::$propertiesOptionsCache[$propertyId])) ? self::$propertiesOptionsCache[$propertyId] : array();
	}
	
	/**
	 * Setter for metadata.
	 * MetaData can have following attributes:
	 * 	name:			string,		automatic generated (see first param), name of the property in DB
	 * 	order: 			int, 		order of the metadata property info
	 * 	label: 			string,		short label of the property
	 * 	description: 	string,		longer description of the property
	 * 	restrictions: 	int,		logic OR of the Restriction constants - defines the allowed values
	 * 	editable: 		boolean,	can be the property edited?
	 * 	visible: 		array of string=>boolean, array of columns, that should be/shouldn't be visible in list (key in the array is the name of the list)
	 * 	method: 		string, 	method name to collect items (FORM_SELECT_FOREIGNKEY and FORM_MULTISELECT_FOREIGNKEY)
	 * 	model: 			string, 	model name (FORM_MULTISELECT_FOREIGNKEY)
	 *
	 * @param string $attrName Name of the attribute
	 * @param array $metaData Description of the attribute
	 * @param array $overload This can everload the metadata array
	 */
	public function addMetaData($metaItem) {
		MetaDataContainer::addMetaData($this->name, $metaItem);
	}

	
	/**
	 * Setter for metadata.
	 * @param string $attrName Name of the attribute, if false, all metadata are returned.
	 * @param string $paramName Name of the parameter of the attribute.
	 * @param mixed $value Metadata definition.
	 */
	public function setMetaData($attrName, $paramName, $value) {
		MetaDataContainer::setMetaData($this->name, $attrName, $paramName, $value);
	}
	
		
	/**
	 * Removes metadata.
	 * @param string $attrName Name of the attribute, if false, all metadata are returned.
	 */
	public function removeMetaData($attrName) {
		MetaDataContainer::removeMetaData($this->name, $attrName);
	}

	/**
	 * Getter for metadata.
	 * @param string $attrName Name of the attribute, if false, all metadata are returned.
	 * @return array metadata for specific $attrName or all, if $attrName is set false
	 */
	public function getMetadata($attrName=false) {
		return MetaDataContainer::getMetaData($this->name, $attrName);
	}

	/**
	 * Returns true if metadata exists.
	 * @param string $attrName Name of the attribute, if false, all metadata are returned.
	 * @return bool Returns true if metadata exists.
	 */
	public function hasMetadata($attrName=false) {
		return MetaDataContainer::hasMetaData($this->name, $attrName);
	}

	
	/**
	 * Setter for properties metadata.
	 * @see self::addMetaData
	 */
	public function addPropertiesMetaData($metaData) {
		switch ($metaData->getType()) {
			case Form::FORM_INPUT_NUMBER:
			case Form::FORM_RADIO:
			case Form::FORM_CHECKBOX:
			case Form::FORM_SELECT:
				$propertyType = AbstractPropertiesModel::VALUE_NUMBER;
				break;
			default:
			case Form::FORM_INPUT_TEXT:
			case Form::FORM_TEXTAREA:
			case Form::FORM_HTML:
			case Form::FORM_INPUT_PASSWORD:
			case Form::FORM_INPUT_IMAGE:
			case Form::FORM_HTML_BBCODE:
				$propertyType = AbstractPropertiesModel::VALUE_STRING;
				break;
			case Form::FORM_INPUT_DATETIME:
			case Form::FORM_INPUT_DATE:
			case Form::FORM_INPUT_TIME:
				$propertyType = AbstractPropertiesModel::VALUE_DATETIME;
				break;
		}
		$metaData->setPropValueType($propertyType);
		$this->properties[$metaData->getName()] = $metaData;
	}
	
	/**
	 *
	 * @param $butotnsSet
	 */
	public function setButtonSet($butotnsSet) {
		$this->buttonsSet = $butotnsSet;
	}
	
	/**
	 * 
	 * @return
	 */
	public function getIcon() {
		return $this->icon;
	}
	
	
	/**
	 * Adds a multi-column index to the table.
	 * @param object $index instance of ModelMetaIndex
	 */
	public function addIndex($index) {
		$this->indexes[$index->name] = $index;
	}
	
	
	/**
	 * Returns indexes from a model
	 * @param object $index instance of ModelMetaIndex
	 * @param bool $ext true if we need indexes from ext table, false otherwise
	 */
	public function getIndexes($ext) {
		$result = $this->indexes;
		foreach ($this->getMetadata() as $meta) {
			if (!$meta->hasSqlIndex() || 
				$this->extendedTextsSupport && 
				($ext && !$meta->getExtendedTable() || !$ext && $meta->getExtendedTable()))
				continue;
			$result[$meta->getSqlIndex()->name] = $meta->getSqlIndex();
		}
		return $result;
	}
	
	/**
	 * Adjusts values of the fields, checks field's format and value.
	 * @param &$newData
	 * @return array List of messages (errors and warnings).
	 */
	public function checkFields(&$newData, &$preddefinedData) {

		$this->adjustAllFieldsValue($newData, $preddefinedData);
		$this->checkAllFieldsValue($newData, $preddefinedData);
		
		return $this->messages;
	}
	

	/**
	 * Returns Fields, that are regular in DB (for setup DB for example)
	 * @return array Fields extracted from metadata, formated to setup DB
	 */
    protected function getFieldsInDB($includeId=true) {
    	$fields = array();
    	$fields_not_in_db = array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE, Form::FORM_CUSTOM);
    	if (!$includeId) {
    		$fields_not_in_db[] = Form::FORM_ID;
    	}

    	foreach ($this->getMetaData() as $field => $meta) {
    		if (!in_array($meta->getType(), $fields_not_in_db)) {
    			$fields[$field] = $field;
    		}
    	}
    	return $fields;
    }
    
    
	/**
	 * Adjusts values of the fields, checks field's format and value.
	 * @param &$newData
	 * @return array List of messages (errors and warnings).
	 */
	public function checkFieldsSelf() {
		$preddefinedData = array();
		foreach ($this->getFieldsInDB(false) as $field) {
			if (!array_key_exists($field, $this->changedValues)) {
				$this->changedValues[$field] = $this->getMetaData($field)->getDefaultValue();
			}
		}
		$this->adjustAllFieldsValue($this->changedValues, $preddefinedData);
		$this->checkAllFieldsValue($this->changedValues, $preddefinedData);
		
		return $this->messages;
	}
	
	/**
	 * Adjusts values of the fields (for example checkbox is 1, if it is set, 0 if not).
	 * @param &$newData
	 */
	protected function adjustAllFieldsValue(&$newData, &$preddefinedData) {
		// adjust standard columns
		foreach ($this->getMetaData() as $field => $meta) {
			if (array_key_exists($field, $preddefinedData)) {
				$this->adjustFieldValue($meta, $preddefinedData, false);
			} else {
				$this->adjustFieldValue($meta, $newData);
			}
		}
		
		// adjust properties
		$propModel = $this->getPropertiesModel();
		if ($propModel) {
			foreach ($propModel->getPossibleProperties() as $field => $meta) {
				if (array_key_exists($field, $preddefinedData)) {
					$this->adjustFieldValue($meta, $preddefinedData, false);
				} else {
					$this->adjustFieldValue($meta, $newData);
				}
			}
		}
	}

	/**
	 * Checks field's value (for example if url is unique).
	 * @param &$newData
	 */
	protected function checkAllFieldsValue(&$newData, &$preddefinedData) {
		// check standard columns
		$inDB = $this->getFieldsInDB(false);
		foreach ($this->getMetaData() as $field => $meta) {
			if (in_array($field, $inDB)) {
				if (array_key_exists($field, $preddefinedData)) {
					$this->checkFieldValue($meta, $preddefinedData);
				} else {
					$this->checkFieldValue($meta, $newData);
				}
			}
		}

		// check properties
		$propModel = $this->getPropertiesModel();
		if ($propModel) {
			foreach ($propModel->getPossibleProperties() as $field => $meta) {
				if (array_key_exists($field, $preddefinedData)) {
					$this->checkFieldValue($meta, $preddefinedData);
				} else {
					$this->checkFieldValue($meta, $newData);
				}
			}
		}
	}
    
    
    /**
     * Method creates the title used in the select box.
     * Should be overwritten.
     * @return string title of the item to use in the select box
     */
    public function makeSelectTitle() {
    	if ($this->hasMetaData('title')) {
    		return $this->title; // TODO: make better
    	}
    	return sprintf("%s object [%d]", get_class($this), $this->id);
    }


	/**
	 * Adjusts values of the field (for example checkbox is 1, if it is set, 0 if not).
	 * @param &$value
	 * @param $metaType
	 * @param $adjustBool if checkbox shoul be adjusted (when used predefined - do not adjust)
	 */
	protected function adjustFieldValue(&$meta, &$newData, $adjustBool=true) {
		$this->adjustBasicFieldValue($meta, $newData, $adjustBool);
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_URL_PART)) {
			$value = isset($newData['title']) ? $newData['title'] : "";
			$this->adjustFunctionFieldUrlPart($meta, $newData, $value);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_PRICE)) {
			$this->adjustFunctionFieldPrice($meta, $newData);
		}
		if ($meta->hasAdjustMethod()) {
			$adjFunc = 'adjustFunction' . $meta->getAdjustMethod();
			if (method_exists($this, $adjFunc)) {
				$newValue = $this->$adjFunc($meta, $newData);
				if ($newValue !== false) {
					$newData[$meta->getName()] = $newValue;
				}
			} else {
				throw new Exception(tg("Adjust function ") . $meta->getAdjustMethod() . tg(" does not exists."));
			}
		}
	}

	/**
	 * Basic adjustement values of the field (for example checkbox is 1, if it is set, 0 if not).
	 * @param &$value
	 * @param $metaType
	 */
	protected function adjustBasicFieldValue(&$meta, &$newData, $adjustBool) {
		if (isset($newData[$meta->getName()])) {
			// we don't need add autolink in the forms..
			$newData[$meta->getName()] = preg_replace('/autolink\!:(\w+)::(\w+)::(\w+)/', 'autolink:$1::$2::$3', $newData[$meta->getName()]);
		}
		switch ($meta->getType()) {
			case Form::FORM_CHECKBOX:
				$value = &$newData[$meta->getName()];
				if ($adjustBool) {
					$value = isset($value);
				}
				break;
			case Form::FORM_INPUT_PASSWORD:
				$value = &$newData[$meta->getName()];
				// we do not hash the password if it should not be changed on empty and the field is empty
				if ($value || !Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_NO_EDIT_ON_EMPTY)) {
					$value = Utilities::hashPassword($value);
				}
				if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_CONFIRM_DOUBLE)) {
					$confirmFieldName = Restriction::R_CONFIRM_PREFIX . $meta->getName();
					// we do not hash the password if it should not be changed on empty and the field is empty
					if ($newData[$confirmFieldName] || !Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_NO_EDIT_ON_EMPTY)) {
						$newData[$confirmFieldName] = Utilities::hashPassword($newData[$confirmFieldName]);
					}
				}
				break;
		}
	}
	
	/**
	 * Checks field's format (for example email).
	 * @param &$meta
	 * @param &$newData all new data from form
	 */
	protected function checkFieldValue(&$meta, &$newData) {
		if (empty($newData[$meta->getName()]) 
			&& (isset($this->id) && $this->id && Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_NO_EDIT_ON_EMPTY) 
				|| Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_EMPTY))) {
			return;
		}

		$value = &$newData[$meta->getName()];	// we cannot touch this value unless we want to make changes in DB
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_NUMBER)) {
			$this->checkFieldFormatNumber($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_PRICE)) {
			$this->checkFieldFormatNumber($value, $meta, 2, true);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_EMAIL)) {
			$this->checkFieldFormatEmail($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_LINK)) {
			$this->checkFieldFormatLink($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_UNIQUE)) {
			$this->checkFieldUnique($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_URL_PART)) {
			$this->checkFieldUrlPart($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_TEXT)) {
			$this->checkFieldText($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_DATE)) {
			$this->checkFieldDate($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_TIME)) {
			$this->checkFieldTime($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_TIMESTAMP)) {
			$this->checkFieldDateTime($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_COLOR_RGBHEXA)) {
			$this->checkColorRGBHexa($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_CONFIRM_DOUBLE)) {
			$this->checkFieldConfirmDouble($value, $meta, $newData);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_NOT_EMPTY)) {
			$this->checkFieldNotEmpty($value, $meta);
		}
		if (Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_HTML)) {
			$this->checkFieldHtml($value, $meta);
		}

		if ($meta->getType() == Form::FORM_HTML_BBCODE) {
			$this->checkFieldBBCode($value, $meta);
		}

		if ($meta->getType() == Form::FORM_UPLOAD_FILE) {
			if ($_FILES[$meta->getName()]['error']) {
				$this->addMessageField('errors', $meta, tg('Error while uploading file: ') . $_FILES[$meta->getName()]['error']);
			}
		}
		
	}

	/**
	 * Checks field's format (for example email).
	 * @param &$value
	 * @param &$meta
	 * @param int $precision if set, value is check for precision (value's
	 *                       precision cannot be better then $precision)
	 */
	protected function checkFieldFormatNumber(&$value, &$meta, $precision=false, $notNegative=false) {
		if (!Utilities::checkNumberFormat($value)) {
			$this->addMessageField("errors", $meta, tg("has wrong format")); 
		}
		if ($precision !== false && round($value, $precision) != $value) {
			$this->addMessageField("errors", $meta, tg("has wrong precision")); 
		}
		if ($notNegative && (float)$value < 0.0) {
			$this->addMessageField("errors", $meta, tg("cannot be negative")); 
		}
	}

	/**
	 * Checks field's format html.
	 * @param &$value
	 * @param &$meta
	 *                       precision cannot be better then $precision)
	 */
	protected function checkFieldHtml(&$value, &$meta) {
		if (($r = Utilities::checkHTMLFormat($value)) !== true) {
			$this->addMessageField("errors", $meta, $r); 
		}
	}

	/**
	 * Checks field's format (for example email).
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldFormatEmail(&$value, &$meta) {
		if ($value && !Utilities::checkEmailFormat($value)) {
			$this->addMessageField("errors", $meta, tg("has wrong format")); 
		}
	}

	/**
	 * Checks field's format (for example link).
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldFormatLink(&$value, &$meta) {
		if (!Utilities::checkLinkFormat($value)) {
			$this->addMessageField("errors", $meta, tg("has wrong format")); 
		}
	}

	
	/**
	 * Checks field's format as YYYY-MM-DD
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldDate(&$value, &$meta) {
		if (!Utilities::checkTimeFormat($value)) {
			$this->addMessageField("errors", $meta, "must have format YYYY-MM-DD"); 
		}
	}

	/**
	 * Checks field's format as HH:MM:SS
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldTime(&$value, &$meta) {
		if (!Utilities::checkDateFormat($value)) {
			$this->addMessageField("errors", $meta, tg("must have format HH:MM:SS")); 
		}
	}

	/**
	* Checks field's format as YYYY-MM-DD HH:MM:SS.
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldDateTime(&$value, &$meta) {
		if (!Utilities::checkDateTimeFormat($value)) {
			$this->addMessageField("errors", $meta, tg("must have format YYYY-MM-DD HH:MM:SS")); 
		}
	}
	
	/**
	 * Checks field's format as #XXYYZZ - hexa color number
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkColorRGBHexa(&$value, &$meta) {
		if (strlen($value) > 0 && !Utilities::checkColorRGBHexaFormat($value)) {
			$this->addMessageField("errors", $meta, tg("must have format #XXXXXX where X is a hexadecimal number")); 
		}
	}

	/**
	* Checks field if the second value is the same as the first (for example password change).
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldConfirmDouble(&$value, &$meta, &$newData) {
		$confirmFieldName = Restriction::R_CONFIRM_PREFIX . $meta->getName();
		if ($newData[$confirmFieldName] != $value) {
			$this->addMessageField("errors", $meta, tg("confirmation has not passed")); 
		}
	}
	
	/**
	 * Checks field's uniquity (for example email in registration).
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldUnique(&$value, &$meta) {
	}
	
	/**
	 * Checks field's format to BBCode syntax.
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldBBCode(&$value, &$meta) {
		if (!Utilities::checkBBCodeFormat($value)) {
			$this->addMessageField("errors", $meta, tg("has not BBCode syntax")); 
		}
	}
	
	/**
	 * Checks field's not to be emtpy.
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldNotEmpty(&$value, &$meta) {
		if ($this->fieldIsEmpty($value, $meta)) {
			$this->addMessageField("errors", $meta, tg("is empty or you have to choose different value")); 
		}
	}
	
	/**
	 * Checks field if not empty.
	 * @param &$value
	 * @param &$meta
	 */
	protected function fieldIsEmpty(&$value, &$meta) {
		return (trim($value) == '');
	}
	
	/**
	 * Checks field's format as a part of url.
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldUrlPart(&$value, &$meta) {
		if (!Utilities::checkUrlPartFormat($value)) {
			$this->addMessageField("errors", $meta, tg("must contain only characters a-z, numbers and \"-\" and cannot be empty"));
		}
	}
	
	/**
	 * Checks field's format as a simple text
	 * @param &$value
	 * @param &$meta
	 */
	protected function checkFieldText(&$value, &$meta) {
		if (!Utilities::checkTextFormat($value)) {
			$this->addMessageField("errors", $meta, tg("must contain only characters a-z, numbers and \"-\" and space"));
		}
	}
	
	/**
	 * Adjust field's format as a part of url.
	 * @param &$value
	 * @param &$meta
	 */
	protected function adjustFunctionFieldUrlPart(&$meta, &$newData, $source) {
	}
	
	/**
	 * Adjust field's format as a part of url.
	 * @param &$value
	 * @param &$meta
	 */
	protected function adjustFunctionFieldPrice(&$meta, &$newData) {
		$value = $newData[$meta->getName()];
		if (!trim($value)) {
			$newData[$meta->getName()] = '0.0';
		}
	}
	
	/**
	 * Adjust field's format as a price
	 * If number contains '.' or ',', last occurence of this character will be 
	 * recognized as the decimal point. The other one will be recognized as the thousand 
	 * separator.
	 * @param &$value
	 * @param &$meta
	 */
	protected function adjustFunctionNumber(&$meta, &$newData) {
		$value = $newData[$meta->getName()];
		$value = trim($value);
		if (preg_match(REGEXP_NUMBER_FORMAT_CS, $value)) {
			// czech format, decimal point is ',' and thousand separator is '.'
			$value = str_replace('.', '', $value);
			$value = str_replace(',', '.', $value);
		} else if (preg_match(REGEXP_NUMBER_FORMAT_EN, $value)) {
			// english format, decimal point is '.' and thousand separator is ','
			$value = str_replace(',', '', $value);
		}
		return $value;
	}
	
	/**
	 * Adjust field to lower case.
	 * @param &$value
	 * @param &$meta
	 */
	protected function adjustFunctionToLower(&$meta, &$newData) {
		return strtolower($newData[$meta->getName()]);
	}
	
	
	protected function addMessage($type, $field, $message) {
		Form::addMessage($this->messages, $type, $field, $message);
	}

	protected function addMessageSimple($type, $message) {
		$this->addMessage($type, "default", $message);
	}

	protected function addMessageField($type, &$meta, $message) {
		$this->addMessage($type, $meta->getName(), tg("Field") . " <strong>" . tg($meta->getLabel()) . "</strong> " . $message . ".");
	}

	private function adjustFunctionCurrentDateTime(&$meta, &$newData) {
		return Utilities::now();
	}
	
	private function adjustFunctionCurrentDateOnEmpty(&$meta, &$newData) {
		if (!$this->id && (!array_key_exists($meta->getName(), $newData) || trim($newData[$meta->getName()]) == '')) {
			return date('Y-m-d');
		}
		return false;
	}
	
	private function adjustFunctionCurrentDateTimeOnEmpty(&$meta, &$newData) {
		if (!$this->id && (!array_key_exists($meta->getName(), $newData) || trim($newData[$meta->getName()]) == '')) {
			return Utilities::now();
		}
		return false;
	}
	
	protected function adjustFunctionNewTokenOnNew(&$meta, &$newData) {
		if (!$this->id && (!array_key_exists($meta->getName(), $newData) || trim($newData[$meta->getName()]) == '')) {
			return Utilities::generateToken();
		}
		return false;
	}
	
	private function adjustFunctionNewMaxOnEmpty(&$meta, &$newData) {
		if (!$this->id) {
			$p = $meta->getName();
			$m = new $this->name();
			// TODO: this does not work with more languages, rank is unique for all of them
			$max = $m->Find($this->name, array(), array(), array("ORDER BY $p DESC", "LIMIT 1"));
			if ($max) {
				return (int)$max[0]->$p + 1;
			}
			return 1;
		}
		return false;
	}
	
	/**
	 * Returns table column names, that can be edited by user.
	 * ID can't be edited for example.
	 * @return array
	 */
	public function getChangeAbleColumns() {
		$columns = array();
		foreach ($this->getMetadata() as $field => $meta) {
			if ($meta->isChangeAble($this->id)) {
				$columns[] = $field;
			}
		}
		return $columns;
	}
	
	/**
	 * Returns table columns, that can be edited by user.
	 * ID can't be edited for example.
	 * @return array
	 */
	public function getChangeAbleOrAutoFilledMetaData() {
		$columns = array();
		foreach ($this->getMetadata() as $field => $meta) {
			if ($meta->isChangeAbleOrAutoFilled($this->hasMetadata('id') ? $this->id : false)) {
				$columns[$field] = $meta;
			}
		}
		return $columns;
	}
	
	/**
	 * add some extra parameters when loading as item collection.
	 */
	public function setLoadParams($params) {
		$this->loadParams = $params;
	}


	/**
	 *
	 * @param $fieldName
	 * @return
	 */
	public function getValue($fieldName) {
		if (array_key_exists($fieldName, $this->databaseValues))
			return $this->databaseValues[$fieldName];
		else
			return null;
	}
	
	
	/**
	 *
	 * @param $fieldName
	 * @return
	 */
	public function getValueView($fieldName) {
		if ($fieldName == 'buttonsSet') {
			return $this->buttonsSet;
		}
		$value = $this->getValue($fieldName);
		$meta = $this->getMetaData($fieldName);
		if (!$meta) {
			return $value;
		}
		switch ($meta->getType()) {
			case Form::FORM_CUSTOM:
				$value = $this->getTableValue();
				break;
			case Form::FORM_ID:
				$value = "<img src=\"" . DIR_ICONS_IMAGES_DIR_THUMBS_URL . "32/" . $this->getIcon() . ".png\" alt=\"\" /><br />" . $value;
				break;
			case Form::FORM_INPUT_TEXT:
				$value = htmlspecialchars(Utilities::truncate(strip_tags($value), 30));
				break;
			case Form::FORM_CHECKBOX: 
				$value = "<div style=\"text-align: center;\">" . ($value 
					? "<img src=\"" . DIR_ICONS_IMAGES_DIR_THUMBS_URL . "24/accept.png\" alt=\"".tg("Yes")."\" />" 
					: "<img src=\"" . DIR_ICONS_IMAGES_DIR_THUMBS_URL . "24/remove.png\" alt=\"".tg("No")."\" />")
					."</div>";
				break;
			case Form::FORM_INPUT_PASSWORD: 
				$value = "";
				break;
			case Form::FORM_TEXTAREA: 
			case Form::FORM_HTML: 
			case Form::FORM_HTML_BBCODE:
				$value = htmlspecialchars(Utilities::truncate(strip_tags($value), 150));
				break;
			case Form::FORM_SELECT:
				$newValue = $value;
				foreach ($meta->getOptions() as $opt) {
					if ($opt['id'] == $value) {
						$newValue = $opt['value'];
					}
				}
				$value = htmlspecialchars(Utilities::truncate(strip_tags($newValue), 30));
				break;
			case Form::FORM_SELECT_FOREIGNKEY: 
				$value = htmlspecialchars(Utilities::truncate(strip_tags($value), 30));
				break;
			case Form::FORM_MULTISELECT_FOREIGNKEY:
			case Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE:
				$newValues = array();
				if (!empty($value)) {
					foreach (explode(';', $value) as $v) {
						$newValues[] = $meta->getOptionsValue($v);
					}
				}
				$value = htmlspecialchars(Utilities::truncate(implode(', ', $newValues), 30));
				break;
			case Form::FORM_MULTISELECT:
				$newValues = array();
				if (!empty($value)) {
					foreach (explode(';', $value) as $v) {
						$newValues[] = $meta->getOptionsValue($v);
					}
				}
				$value = htmlspecialchars(Utilities::truncate(implode(', ', $newValues), 30));
				break;
			case Form::FORM_INPUT_IMAGE:
				$thumb = new Thumbnail(null, $value, 120, 80, 'b');
				$value = '<img src="' . $thumb->getThumbnailImagePath() . '" alt="#" />';
				break;
			default: 
				break;
		}
		return $value;
	}
	
	
	public function setPredefinedValues($values) {
		$this->predefinedValues = $values;
	}
	
	
	public function getChanges() {
		return array();
	}
	
}

?>

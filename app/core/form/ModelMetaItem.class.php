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
 * Class handles metadata about models (it describes attributes of the model).
 * It includes DB connections and properties' settings.
 */

class ModelMetaItem {
	
	const UNDEFINED = -1;
	const NEVER = 0;
	const ALWAYS = 1;
	const ON_NEW = 2;
	const ON_EDIT = 3;
	
	private $name;					// name of the item (only a-z_ is possible)
	private $type;					// type of the item (@see Form::...)
	private $order;					// order of the item is defined automaticly
	private $defaultValue = null; 	// default value
	private $label = ''; 			// short label of the item
	private $description = ''; 		// longer description of the item (several words)
	private $restrictions = 0;		// for restrictions @see Restriction::R_...
	private $isEditable = self::ALWAYS; 	// can be eddited in form true/false/1=only new
	private $isVisibleInForm = self::UNDEFINED; 	// can be displayed in form (null = undefined, true/false = on/off)
	private $isVisible = true; 	// where the item is visible (mostly standard item lists)
	private $optionsMethod = '';	// method to be called to select possible values
	private $optionsModel = '';		// model that is used to select possible values
	private $sqlType = '';			// type of the value in SQL
	private $sqlIndex = null; 		// index in SQL (see ModelMetaIndex class)
	private $adjustMethod = null;		// value should be adjusted before checking
	private $adjustBeforeSavingMethod = null;
	private $checkMethod = null;		// value should be done as validity checking
	private $isAutoFilled = self::NEVER;	// null means only on the inserting a new item
	private $wysiwygType = Javascript::WYSIWYG_FULL;	// type of wysiwyg configuration
	private $formTab = null;		// tab where the item should be in (@see Form::TAB_... constants)
	private $extendedTable = false;	// column is in the extending table (where text are stored and can be translated)
	private $options = null;		// static options
	private $optionsMustBeSelected = null;		// options must be selected
	private $optionsShouldBeTranslated = false;	// options should be translated
	private $valueAdjustPattern = null;			// adjust pattern used by filters 
	private $valueConditionPattern = null;		// adjust condition used by filters
	private $selector = false;					// wether to use a selector
	private $selectorDisplayMode = null; 		// selector mode
	private $renderObject = null;				// object that renders the form item
	private $propValueType = null;	// type of value by property
	private $lineClass = null;		// CSS class of the line
	private $linkNewItem = null;
	private $style = null;
	private $uploadDir =  '';
	private $updateHandleDefault = false;
	private $uploadMultipleFiles = false;
	private $isInDb = null;
	private $useSalt = true;
	
	static private $newOrder = 1;
	
	/**
	 * Constructor
	 */
	function __construct($name) {
		$this->name = $name;
		$this->order = self::$newOrder++;
	}
	
	public function setParam($name, $value) {
		$this->$name = $value;
		return $this->$name;
	}
	
	
	public function isChangeAble($id) {
		return $this->getIsEditable() == self::ALWAYS 
			|| ($this->getIsEditable() == self::ON_NEW && !$id)
			|| ($this->getIsEditable() == self::ON_EDIT && $id);
	}

	
	public function isChangeAbleOrAutoFilled($id) {
		return $this->isChangeAble($id) 
			|| ($this->getIsAutoFilled($id));
	}

	
	/**
	 * Getter
	 */
	public function getDefaultValue() {
		return $this->defaultValue;
	}
	
	/**
	 * Setter
	 */
	public function setDefaultValue($value) {
		$this->defaultValue = $value;
		return $this;
	}

	public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

	public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getRestrictions() {
        return $this->restrictions;
    }

    public function hasRestrictions($what) {
        return $this->restrictions & $what;
    }

    public function setRestrictions($restrictions) {
        $this->restrictions = $restrictions;
        return $this;
    }

    public function addRestrictions($restrictions) {
        $this->restrictions |= $restrictions;
        return $this;
    }

    public function removeRestrictions($restrictions) {
        $this->restrictions &= ~$restrictions;
        return $this;
    }

    public function getIsEditable() {
        return $this->isEditable;
    }

    public function setIsEditable($isEditable) {
        $this->isEditable = $isEditable;
        return $this;
    }

    public function getIsVisible($identificator=null) {
    	if (!$identificator || !array_key_exists($identificator, $this->isVisible)) {
    		return $this->isVisible;
        }
   		return $this->isVisible[$identificator];
    }

    public function setIsVisible($isVisible) {
        $this->isVisible = $isVisible;
        return $this;
    }

    public function getOptionsMethod() {
        return $this->optionsMethod;
    }

    public function setOptionsMethod($optionsMethod) {
        $this->optionsMethod = $optionsMethod;
        return $this;
    }

    public function getOptionsModel() {
        return $this->optionsModel;
    }

    public function setOptionsModel($optionsModel) {
        $this->optionsModel = $optionsModel;
        return $this;
    }

    public function getSqlType() {
        return $this->sqlType;
    }

    public function setSqlType($sqlType) {
        $this->sqlType = $sqlType;
        return $this;
    }

    public function hasSqlIndex() {
        return $this->sqlIndex !== null;
    }

    public function getSqlIndex() {
        return $this->sqlIndex;
    }

    public function setSqlIndex($type, $columns=false) {
    	if (!$columns)
    		$columns = array($this->name);
        $this->sqlIndex = new ModelMetaIndex($columns, $type);
        return $this;
    }

    public function removeSqlIndex() {
        $this->sqlIndex = null;
        return $this;
    }

	public function getCheckMethod() {
        return $this->checkMethod;
    }

    public function setCheckMethod($checkMethod) {
        $this->checkMethod = $checkMethod;
        return $this;
    }

	public function getAdjustMethod() {
        return $this->adjustMethod;
    }

    public function getAdjustBeforeSavingMethod() {
        return $this->adjustBeforeSavingMethod;
    }

    public function hasAdjustMethod() {
        return $this->adjustMethod !== null;
    }

    public function hasAdjustBeforeSavingMethod() {
        return $this->adjustBeforeSavingMethod !== null;
    }

    public function setAdjustMethod($adjustMethod) {
        $this->adjustMethod = $adjustMethod;
        return $this;
    }

    public function setAdjustBeforeSavingMethod($adjustMethod) {
        $this->adjustBeforeSavingMethod = $adjustMethod;
        return $this;
    }
    
    public function getIsAutoFilled($id) {
		return $this->isAutoFilled == self::ALWAYS 
			|| ($this->isAutoFilled == self::ON_NEW && !$id)
			|| ($this->isAutoFilled == self::ON_EDIT && $id);
    }

    public function setIsAutoFilled($isAutoFilled) {
        $this->isAutoFilled = $isAutoFilled;
        return $this;
    }

    public function getWysiwygType() {
        return $this->wysiwygType;
    }

    public function setWysiwygType($wysiwygType) {
        $this->wysiwygType = $wysiwygType;
        return $this;
    }

    public function getFormTab() {
        return $this->formTab;
    }

    public function setFormTab($formTab) {
        $this->formTab = $formTab;
        return $this;
    }

    public function getExtendedTable() {
        return $this->extendedTable;
    }

    public function setExtendedTable($extendedTable) {
        $this->extendedTable = $extendedTable;
        return $this;
    }

    public function hasOptions() {
        return count($this->options) > 0;
    }

    public function getOptions() {
        return $this->options;
    }

    public function getOptionsValue($id) {
        return $this->options[$id];
    }

    public function setOptions($options) {
        $this->options = $options;
        return $this;
    }

    public function getOptionsMustBeSelected() {
        return $this->optionsMustBeSelected;
    }

    public function setOptionsMustBeSelected($optionsMustBeSelected) {
        $this->optionsMustBeSelected = $optionsMustBeSelected;
        return $this;
    }

    public function getOptionsShouldBeTranslated() {
        return $this->optionsShouldBeTranslated;
    }

    public function setOptionsShouldBeTranslated($optionsShouldBeTranslated) {
        $this->optionsShouldBeTranslated = $optionsShouldBeTranslated;
        return $this;
    }
    public function getValueAdjustPattern() {
        return $this->valueAdjustPattern;
    }

    public function setValueAdjustPattern($valueAdjustPattern) {
        $this->valueAdjustPattern = $valueAdjustPattern;
        return $this;
    }

    public function getValueConditionPattern() {
        return $this->valueConditionPattern;
    }

    public function setValueConditionPattern($valueConditionPattern) {
        $this->valueConditionPattern = $valueConditionPattern;
        return $this;
    }
    public function getSelector() {
        return $this->selector;
    }

    public function setSelector($selector) {
        $this->selector = $selector;
        return $this;
    }

    public function getSelectorDisplayMode() {
        return $this->selectorDisplayMode;
    }

    public function setSelectorDisplayMode($selectorDisplayMode) {
        $this->selectorDisplayMode = $selectorDisplayMode;
        return $this;
    }

    public function getRenderObject() {
        return $this->renderObject;
    }

    public function setRenderObject(&$renderObject) {
        $this->renderObject = $renderObject;
        return $this;
    }

    public function getPropValueType() {
        return $this->propValueType;
    }

    public function setPropValueType($propValueType) {
        $this->propValueType = $propValueType;
        return $this;
    }

    public function getLineClass() {
        return $this->lineClass;
    }

    public function setLineClass($lineClass) {
        $this->lineClass = $lineClass;
        return $this;
    }

    public function getLinkNewItem() {
        return $this->linkNewItem;
    }

    public function setLinkNewItem($linkNewItem) {
        $this->linkNewItem = $linkNewItem;
        return $this;
    }

    public function getStyle() {
        return $this->style;
    }

    public function setStyle($style) {
        $this->style = $style;
        return $this;
    }

    /**
	 * Getter
	 */
	public function getUploadDir() {
		return $this->uploadDir;
	}
	
	/**
	 * Setter
	 */
	public function setUploadDir($uploadDir) {
		$this->uploadDir = $uploadDir;
		return $this;
	}

    /**
	 * Getter
	 */
	public function getUpdateHandleDefault() {
		return $this->updateHandleDefault;
	}
	
	/**
	 * Setter
	 */
	public function setUpdateHandleDefault($updateHandleDefault) {
		$this->updateHandleDefault = $updateHandleDefault;
		return $this;
	}

    /**
	 * Getter
	 */
	public function getIsVisibleInForm($id=-1) {
		if ($id === -1) {
			return $this->isVisibleInForm;
		}
		return $this->isVisibleInForm == self::ALWAYS
			|| ($this->isVisibleInForm == self::ON_NEW && !$id)
			|| ($this->isVisibleInForm == self::ON_EDIT && $id);
	}
	
	/**
	 * Setter
	 */
	public function setIsVisibleInForm($isVisibleInForm) {
		$this->isVisibleInForm = $isVisibleInForm;
		return $this;
	}

    /**
	 * Getter
	 */
	public function getUploadMultipleFiles() {
		return $this->uploadMultipleFiles;
	}

    /**
	 * Setter
	 */
	public function setUploadMultipleFiles($uploadMultipleFiles) {
		$this->uploadMultipleFiles = $uploadMultipleFiles;
		return $this;
	}

    /**
	 * Getter
	 */
	public function getForceIsInDb() {
		return $this->isInDb;
	}

    /**
	 * Setter
	 */
	public function setForceIsInDb($isInDb) {
		$this->isInDb = $isInDb;
		return $this;
	}
	
	
	/**
	 * Returns true if field is in DB.
	 */
	public function getIsInDB($includeId=true) {
		/* if defined $this->isInDb, we just use it */
		if ($this->isInDb !== null)
			return $this->isInDb;
		
		$fields_not_in_db = array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE, Form::FORM_SPECIFIC_NOT_IN_DB);
    	if (!$includeId)
    		$fields_not_in_db[] = Form::FORM_ID;
    	
		return !in_array($this->getType(), $fields_not_in_db);
	}

	public function hashValue($value, $email='') {
		if ($this->getUseSalt())
			return Utilities::hashPasswordSalt(Utilities::hashPassword($value), $email);
		else
			return Utilities::hashPassword($value);
	}
	
	public function setUseSalt($useSalt) {
		$this->useSalt = $useSalt;
		return $this;
	}
	
	public function getUseSalt() {
		return $this->useSalt;
	}
}

?>

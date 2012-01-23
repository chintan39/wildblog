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


class AbstractPropertiesDefinitionModel extends AbstractDefaultModel {
	
	var $package = 'Abstract';
	var $icon = 'properties', $table = 'properties_definition';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$this->addMetaData(ModelMetaItem::create('prop_label')
			->setLabel('Label')
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('prop_name')
			->setLabel('Name')
			->setRestrictions(Restriction::R_URL_PART | Restriction::R_UNIQUE)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL')
			->setSqlIndex('unique'));
    	
		$this->addMetaData(ModelMetaItem::create('prop_description')
			->setLabel('Description')
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
		
		
		$typeOptions = array(
			array('id' => Form::FORM_INPUT_NUMBER, 'value' => 'Number field'),
			array('id' => Form::FORM_INPUT_TEXT, 'value' => 'Text field'),
			array('id' => Form::FORM_TEXTAREA, 'value' => 'Textarea'),
			array('id' => Form::FORM_CHECKBOX, 'value' => 'Checkbox'),
			array('id' => Form::FORM_SELECT, 'value' => 'Selectbox'),
			array('id' => Form::FORM_INPUT_DATETIME, 'value' => 'Date and Time'),
			array('id' => Form::FORM_INPUT_DATE, 'value' => 'Date'),
			array('id' => Form::FORM_INPUT_TIME, 'value' => 'Time'),
			array('id' => Form::FORM_INPUT_IMAGE, 'value' => 'Image'),
			);
		
		$this->addMetaData(ModelMetaItem::create('prop_type')
			->setLabel('Type')
			->setType(Form::FORM_SELECT)
			->setOptions($typeOptions)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\''));
		
		
    }
    
    
    protected function getDbTypeFromFormType($formType) {
		$map = array(
			Form::FORM_INPUT_NUMBER => AbstractPropertiesModel::VALUE_NUMBER,
			Form::FORM_INPUT_TEXT => AbstractPropertiesModel::VALUE_STRING,
			Form::FORM_TEXTAREA => AbstractPropertiesModel::VALUE_STRING,
			Form::FORM_CHECKBOX => AbstractPropertiesModel::VALUE_NUMBER,
			Form::FORM_SELECT => AbstractPropertiesModel::VALUE_NUMBER,
			Form::FORM_INPUT_DATE => TIMEAbstractPropertiesModel::VALUE_DATETIME,
			Form::FORM_INPUT_DATE => AbstractPropertiesModel::VALUE_DATETIME,
			Form::FORM_INPUT_TIME => AbstractPropertiesModel::VALUE_DATETIME,
			Form::FORM_INPUT_IMAGE => AbstractPropertiesModel::VALUE_STRING,
		);
		return $map[$formType];
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    //$this->addCustomRelation('CommodityProductsModel', 'product', 'id'); // define a 1:many relation to category 
	}

	/**
	 * Adjust field's format as a part of url.
	 * @param &$value
	 * @param &$meta
	 */
	protected function adjustFunctionFieldUrlPart(&$meta, &$newData, $source) {
		// todo: this is repeatitive code, defined in abstractNodesModel too; 
		// should be defined only on one place
		$value =& $newData[$meta->getName()];
		$value = trim($value);
		if ($source == '') {
			$source = $this->nameShort;
		}
		if ($value == "") {
			$value = Utilities::makeUrlPartFormat($source);
			$suffix = 0;
			while ($this->fieldIsNotUnique($value, $meta) || $this->fieldIsEmpty($value, $meta)) {
				$value = Utilities::makeUrlPartFormat($source . '-' . $suffix);
				$suffix += 1;
			}
		}
	}
}

?>

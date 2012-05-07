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
 * 
 */
 
class BaseChangesModel extends AbstractDefaultModel {
	
	var $package = 'Base';
	var $icon = 'hammer';
	var $table = 'changes';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('packagename')
			->setLabel('Package')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(AtributesFactory::create('model')
			->setLabel('Model')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(AtributesFactory::create('item')
			->setLabel('Item ID')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(AtributesFactory::create('field')
			->setLabel('Field')
			->setType(Form::FORM_INPUT_TEXT)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('VARCHAR(64) NOT NULL'));

		$this->addMetaData(AtributesFactory::create('data')
			->setLabel('Data')
			->setType(Form::FORM_TEXTAREA)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('TEXT NOT NULL'));

		$this->addMetaData(AtributesFactory::stdInserted()->setIsEditable(ModelMetaItem::NEVER)->removeSqlIndex());
    	$this->addMetaData(AtributesFactory::stdIP()->setIsEditable(ModelMetaItem::NEVER));

		$this->addIndex(new ModelMetaIndex(array('package', 'model', 'item'), ModelMetaIndex::UNIQUE));
    }
    
    /**
     * Static constructor.
     */
    public static function create($package, $model, $item, $field, $data) {
    	$change = new self();
    	$change->packagename = $package;
    	$change->model = $model;
    	$change->item = $item;
    	$change->field = $field;
    	$change->data = $data;
    	return $change;
    }
}


?>

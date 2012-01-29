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


class AbstractProductionProductsModel extends AbstractNodesModel {

	var $package = 'AbstractProduction';
	var $icon = 'product', $table = 'products';
	var $propertiesModelName = 'PropertiesModel'; // change
	
    protected function attributesDefinition() {
    	
    	$this->propertiesModelName = $this->package . $this->propertiesModelName;
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('manofacturer')
			->setLabel('Manofacturer')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
    	
		/*
		$this->addMetaData('category', array(
			->setLabel('Category',
			->setType(Form::FORM_SELECT_FOREIGNKEY,
			->setOptionsMethod('listSelect',
			->setSqlType('int(11) NOT NULL DEFAULT '0'',
			->setSqlIndex('index',
			));
		*/
		
		$this->addMetaData(ModelMetaItem::create('categoriesProductsConnection')
			->setLabel('Categories')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
		$this->addMetaData(AtributesFactory::stdText());
		$this->addMetaData(AtributesFactory::stdImage());

		$this->addMetaData(ModelMetaItem::create('unit')
			->setLabel('Unit')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));

		$this->addMetaData(ModelMetaItem::create('vat')
			->setLabel('Vat')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));

		$this->addMetaData(AtributesFactory::stdPrice());
		$this->addMetaData(AtributesFactory::stdPrice()
			->setName('price_original') 
			->setLabel('Original price')
			->setIsVisible(ModelMetaItem::NEVER));
		$this->addMetaData(AtributesFactory::stdPrice()
			->setName('price_without_discount') 
			->setLabel('Price without discount')
			->setIsVisible(ModelMetaItem::NEVER));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    // old $this->addCustomRelation($this->package . 'CategoriesModel', 'category', 'id'); // define a 1:many relation to category 
	    $this->addCustomRelation($this->package . 'ManofacturersModel', 'manofacturer', 'id'); // define a 1:many relation to manofacturer 
	    $this->addCustomRelation($this->package . 'VatModel', 'vat', 'id'); // define a 1:many relation to vat 
	    $this->addCustomRelation($this->package . 'UnitsModel', 'unit', 'id'); // define a 1:many relation to vat 
        $this->addCustomRelationMany($this->package . 'CategoriesModel', $this->package . 'CategoriesProductsModel', 'product', 'category', 'categoriesProductsConnection'); // define a many:many relation to Tag through BlogTag
	}
	
	public function loadProperties($onlyProperties=false) {
		$prop = new $this->propertiesModelName();
		
		// init all possible properties
		$propertiesMeta = $prop->getPossibleProperties();
		foreach ($propertiesMeta as $propMeta) {
			$propName = $propMeta['name'];
			$this->addNonDbProperty($propName);
			$this->$propName = '';
		}

		// get properties from DB
		$properties = $prop->getPropertiesItem($this, $onlyProperties);
		if ($properties) {
			foreach ($properties as $propItem) {
				$propName = $propItem->value_name;
				$this->addNonDbProperty($propName);
				switch ($propItem->value_type) {
				case AbstractPropertiesModel::VALUE_NUMBER: $this->$propName = $propItem->value_number; break;
				case AbstractPropertiesModel::VALUE_STRING: $this->$propName = $propItem->value_string; break;
				case AbstractPropertiesModel::VALUE_DATETIME: $this->$propName = $propItem->value_datetime; break;
				default: break;
				}
			}
		}
	}
} 

?>
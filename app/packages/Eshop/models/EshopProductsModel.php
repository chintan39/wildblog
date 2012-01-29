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


class EshopProductsModel extends AbstractNodesModel {

	var $package = 'Eshop';
	var $icon = 'product', $table = 'products';
	var $propertiesModelName = 'EshopProductsPropertiesModel';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('manofacturer')
			->setLabel('Manofacturer')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
    	
		$this->addMetaData(ModelMetaItem::create('category')
			->setLabel('Category')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
		
		$this->addMetaData(AtributesFactory::stdText());
		$this->addMetaData(AtributesFactory::stdImage());
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('EshopCategoriesModel', 'category', 'id'); // define a 1:many relation to category 
	    $this->addCustomRelation('EshopManofacturersModel', 'manofacturer', 'id'); // define a 1:many relation to manofacturer 
	}
	
	public function loadProperties($onlyProperties=false) {
		$prop = new EshopProductsPropertiesModel();
		
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
				case PropertiesModel::VALUE_NUMBER: $this->$propName = $propItem->value_number; break;
				case PropertiesModel::VALUE_STRING: $this->$propName = $propItem->value_string; break;
				case PropertiesModel::VALUE_DATETIME: $this->$propName = $propItem->value_datetime; break;
				default: break;
				}
			}
		}
	}


} 

?>
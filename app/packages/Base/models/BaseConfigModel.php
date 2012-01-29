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
 
class BaseConfigModel extends AbstractDefaultModel {
	
	var $package = 'Base';
	var $icon = 'settings';
	var $table = 'config';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {

    	parent::attributesDefinition();
    	
		$options = array();
		foreach (Config::$meta as $key => $item) {
			$options[] = array(
				'id' => $key, 
				'value' => $item->label ? $item->label : ucfirst(strtolower(str_replace('_', ' ', $key))),
				'disabled' => $item->inDB);
		}
		
		Utilities::arrayValueSort($options, 'value', 'asc', 'arraystring');

		$this->addMetaData(AtributesFactory::create('key')
			->setLabel('Key')
			->setType(Form::FORM_SELECT)
			->setSqlType('VARCHAR(64) NOT NULL')
			->setSqlIndex('unique')
			->setOptions($options)
			->setOptionsMustBeSelected(true)
			->setOptionsShouldBeTranslated(true)
			->setRestrictions(Restriction::R_NOT_EMPTY | Restriction::R_UNIQUE | Restriction::R_NO_EDIT_ON_EMPTY)
			->setIsVisibleInForm(ModelMetaItem::ALWAYS)
			->setIsEditable(ModelMetaItem::ON_NEW));

    	$this->addMetaData(AtributesFactory::stdText()->setType(Form::FORM_TEXTAREA));
    	
    	$this->addMetaData(AtributesFactory::stdDescription());
    	
    }
    
    public function loadConfig() {
		$mainConfig = $this->loadCache('mainConfig');
		if (!$mainConfig) {
			$mainConfig = BaseConfigModel::Search('BaseConfigModel');
			$this->saveCache('mainConfig', $mainConfig, array('BaseConfigModel'));
		}
    	return $mainConfig;
    }
    
	public function Save($forceSaving=false) 
	{
		Environment::getPackage('Base')->getController('Cache')->actionClearCache(null);
		return parent::Save($forceSaving);
	}
	
	public function getValue($fieldName) {
		if (array_key_exists($fieldName, $this->databaseValues))
			return $this->databaseValues[$fieldName];
		if ($fieldName == 'text' && isset($this->predefinedValues['key']))
			return Config::GetCond($this->predefinedValues['key'], '');
		return null;
	}

	public function setPredefinedValues($values) {
		parent::setPredefinedValues($values);
		if (isset($this->predefinedValues['key']) && Config::Exists($this->predefinedValues['key'])) {
			$this->getMetaData('key')->setIsEditable(ModelMetaItem::NEVER);
		}
	}
	
}


?>

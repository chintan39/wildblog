<?php

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

		$this->addMetaData(ModelMetaItem::create('key')
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

    	$this->addMetaData(AbstractAttributesModel::stdText()->setType(Form::FORM_TEXTAREA));
    	
    	$this->addMetaData(AbstractAttributesModel::stdDescription());
    	
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
}


?>

<?php

/**
 * 
 */
 
class BaseDictionaryModel extends AbstractDefaultModel {
	
	const KIND_PROJECT_SPECIFIC = 1;
	const KIND_GENERAL = 2;
	const KIND_URL_PARTS = 3;
	
	var $package = 'Base';
	var $icon = 'dictionary';
	var $table = 'dictionary';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('key')
			->setLabel('Pattern')
			->setDescription('Text or phrase to translate')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('BLOB NOT NULL'));

		$this->addMetaData(ModelMetaItem::create('language')
			->setLabel('Language')
			->setDescription('Language of the translated text')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));
		
		$this->addIndex('key_language', 'unique', array('key|' . DICTIONARY_KEY_LENGTH, 'language', 'kind'));
		
    	$this->addMetaData(AbstractAttributesModel::stdText()
    		->setType(Form::FORM_TEXTAREA) 
    		->setLabel('Translation')
    		->setDescription('Your translation of the text or phrase'));

		$kindOptions = array(
			array('id' => self::KIND_PROJECT_SPECIFIC, 'value' => 'Project specific'),
			array('id' => self::KIND_GENERAL, 'value' => 'General'),
			array('id' => self::KIND_URL_PARTS, 'value' => 'URL parts'),
		);
		$this->addMetaData(ModelMetaItem::create('kind')
			->setLabel('Kind')
			->setType(Form::FORM_SELECT)
			->setOptions($kindOptions)
			->setSqlType('int(3) NOT NULL DEFAULT \'1\''));
		
		$this->addMetaData(ModelMetaItem::create('automatic')
			->setLabel('Automatic')
			->setDescription('Need to be checked or changed')
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(3) NOT NULL DEFAULT \'1\''));
		
    	
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('BaseLanguagesModel', 'language', 'id'); // define a relation to Language
    }
    
    public function loadDict($lang) {
		$cache = $this->loadCache('dictionary_' . $lang);
		if ($cache) {
			return $cache;
		}
    	$result = array(
			self::KIND_PROJECT_SPECIFIC => array(),
			self::KIND_GENERAL => array(),
			self::KIND_URL_PARTS => array(),
		);	
    	$tmpDict = $this->Find($this, array('language = ?'), array($lang));
		if ($tmpDict) {
			foreach ($tmpDict as $item) {
				$result[$item->kind][$item->key] = $item->text;
			}
		}
		$this->saveCache('dictionary_' . $lang, $result, array($this->name));
		return $result;
    }


    public function loadUrlDict() {
		$cache = $this->loadCache('urldictionary_');
		if ($cache) {
			return $cache;
		}
    	$result = array();
    	foreach (Language::getAll() as $lang) {
    		$tmpDict = $this->Find($this, array('language = ? AND kind = ?'), array($lang['id'], BaseDictionaryModel::KIND_URL_PARTS));
    		$result[$lang['id']] = array();
			if ($tmpDict) {
				foreach ($tmpDict as $item) {
					$result[$lang['id']][$item->key] = $item->text;
				}
			}
    	}
		$this->saveCache('urldictionary_', $result, array($this->name));
		return $result;
    }

    /**
     * Clear 'automatic' attribute before saving.
     */
    public function Save($forceSaving=false, $clearAutomatic=true) {
    	if ($clearAutomatic) {
    		$this->automatic = 0;
    	}
    	return parent::Save($forceSaving);
    }
}


?>

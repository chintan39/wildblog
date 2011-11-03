<?php 

class BaseDatabaseTableModel extends AbstractBasicModel {
	
    function __construct($id = false, $forceLanguage = false) {
    	$this->id = $id;
    }

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('id')
			->setLabel('ID'));
    	
		$this->addMetaData(ModelMetaItem::create('table')
			->setLabel('Table'));
    	
		$this->addMetaData(ModelMetaItem::create('model')
			->setLabel('Model'));

		$this->addMetaData(ModelMetaItem::create('columns')
			->setLabel('Columns'));

    	$this->addMetaData(AbstractAttributesModel::stdText()->setType(Form::FORM_TEXTAREA));

    }

	public function getValue($fieldName) {
		return $this->$fieldName;
	}
	
}

?>

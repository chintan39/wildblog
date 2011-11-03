<?php 

class BaseTestsPackageModel extends AbstractBasicModel {
	
    function __construct($id = false, $forceLanguage = false) {
    	$this->id = $id;
    }
    
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('id')
			->setLabel('ID'));
    	
		$this->addMetaData(ModelMetaItem::create('description')
			->setLabel('Description'));
    	
		$this->addMetaData(ModelMetaItem::create('package')
			->setLabel('Package'));

    }

	public function getValue($fieldName) {
		return $this->$fieldName;
	}
	
}

?>

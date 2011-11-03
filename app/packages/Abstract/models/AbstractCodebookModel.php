<?php


class AbstractCodebookModel extends AbstractDefaultModel {

	var $package="Abstract";

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdTitle());
    	$this->addMetaData(AbstractAttributesModel::stdUrl());
    	$this->addMetaData(AbstractAttributesModel::stdText()->setType(Form::FORM_INPUT_TEXT));
		$this->addMetaData(AbstractAttributesModel::stdRank());
    	
    }
    
    
	/**
	 * Adjust field's format as a part of url.
	 * @param &$value
	 * @param &$meta
	 */
	protected function adjustFunctionFieldUrlPart(&$meta, &$newData, $source) {
		// todo: this is dupplicate code, defined in abstractNodesModel too; 
		// should be defined only on one place
		$value =& $newData[$meta->getName()];
		$value = trim($value);
		if ($source == '') {
			$source = $this->nameShort;
		}
		if ($value == '') {
			$value = Utilities::makeUrlPartFormat();
		}
		$suffix = 0;
		while ($this->fieldIsNotUnique($value, $meta) || $this->fieldIsEmpty($value, $meta)) {
			$value = Utilities::makeUrlPartFormat($source . '-' . $suffix);
			$suffix += 1;
		}
	}

}

?>

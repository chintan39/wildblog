<?php


class AbstractNodesModel extends AbstractSimpleModel {

	var $package="Abstract";

	protected function attributesDefinition() {
		
		parent::attributesDefinition();
		
		$this->addMetaData(AbstractAttributesModel::stdTitle());
		$this->addMetaData(AbstractAttributesModel::stdUrl());
	}

	/**
	 * Adjust field's format as a part of url.
	 * @param &$value
	 * @param &$meta
	 */
	protected function adjustFunctionFieldUrlPart(&$meta, &$newData, $source) {
		$value =& $newData[$meta->getName()];
		$value = trim($value);
		if ($source == '') {
			$source = $this->nameShort;
		}
		if ($value == '') {
			$value = Utilities::makeUrlPartFormat($source);
		}
		$suffix = 0;
		while ($this->fieldIsNotUnique($value, $meta) || $this->fieldIsEmpty($value, $meta)) {
			$value = Utilities::makeUrlPartFormat($source . '-' . $suffix);
			$suffix += 1;
		}
	}

    /**
     * Method creates the title used in the select box.
     * @return string title of the item to use in the select box
     */
    public function makeSelectTitle() {
    	return $this->title;
    }
    

}

?>

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

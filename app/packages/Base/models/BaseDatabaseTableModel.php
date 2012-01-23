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

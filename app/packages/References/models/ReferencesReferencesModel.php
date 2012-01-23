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


class ReferencesReferencesModel extends AbstractPagesModel {

	var $package = 'References';
	var $icon = 'references', $table = 'references';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdFirstname()->setRestrictions(Restriction::R_NOT_EMPTY));
    	$this->addMetaData(AbstractAttributesModel::stdSurname()->setRestrictions(Restriction::R_NOT_EMPTY));
    	$this->addMetaData(AbstractAttributesModel::stdCity());
    	$this->addMetaData(AbstractAttributesModel::stdEmail()->setRestrictions(Restriction::R_NOT_EMPTY));
    	
    	$this->removeMetaData('description');

    	$this->getMetaData('text')->setType(Form::FORM_TEXTAREA);
    	
		$options = array();
		for ($i=1; $i<=5; $i++)
			$options[] = array('id' => $i, 'value' => $i);

    	$this->addMetaData(ModelMetaItem::create('rating')
			->setLabel('Rating')
			->setDescription('how much do you like it')
			->setType(Form::FORM_SELECT)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setOptions($options)
			->setOptionsMustBeSelected(true));
    }


} 

?>
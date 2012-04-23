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
 
class BaseExportImportModel extends AbstractVirtualModel {
	
	var $package = 'Base';
	var $icon = 'import_export';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('importdata')
			->setLabel('Data to import')
			->setType(Form::FORM_UPLOAD_FILE));

		$this->addMetaData(AtributesFactory::create('separator')
			->setLabel('Separator (default \';\')')
			->setType(Form::FORM_INPUT_TEXT));

		$this->addMetaData(AtributesFactory::create('quotation')
			->setLabel('Quotation (default: \'"\')')
			->setType(Form::FORM_INPUT_TEXT));

		$this->addMetaData(AtributesFactory::create('encoding')
			->setLabel('Encoding (default UTF-8)')
			->setType(Form::FORM_INPUT_TEXT));

		$this->addMetaData(AtributesFactory::create('columns')
			->setLabel('Columns separated by comma')
			->setType(Form::FORM_INPUT_TEXT));

    }
    
    
}


?>

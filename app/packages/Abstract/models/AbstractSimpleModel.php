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


require_once('AbstractDefaultModel.php');

class AbstractSimpleModel extends AbstractDefaultModel {

	var $package='Abstract';

    protected function attributesDefinition() {

		parent::attributesDefinition();
		
		$this->addMetaData(AtributesFactory::stdInserted());
		$this->addMetaData(AtributesFactory::stdUpdated());
		$this->addMetaData(AtributesFactory::stdActive());
    }

    /**
     * Adds qualification to active property.
     * Must be 1 standardly.
     */
	protected function qualificationDefinition() {
		parent::qualificationDefinition();
		$this->qualification['active'] = array(new ItemQualification('active = ?', 1));
	}
	
}

?>

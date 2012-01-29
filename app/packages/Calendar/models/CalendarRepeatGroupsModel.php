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


class CalendarRepeatGroupsModel extends AbstractDefaultModel {
	
	var $package = 'Calendar';
	var $icon = 'tag', $table = 'repeat_groups';
    
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$periodOptions = array(
			array('id' => 0, 'value' => 'no repeat'),
			array('id' => 1, 'value' => 'days'),
			array('id' => 2, 'value' => 'weeks'),
			array('id' => 3, 'value' => 'months'),
			array('id' => 4, 'value' => 'years'),
			);
		
		$this->addMetaData(AtributesFactory::create('repeat_period')
			->setLabel('repeat_period')
			->setType(Form::FORM_SELECT)
			->setOptions($periodOptions)
			->setSqltype('int(11) NOT NULL DEFAULT \'0\''));

		$this->addMetaData(AtributesFactory::create('repeat_times')
			->setLabel('repeat_times')
			->setType(Form::FORM_INPUT_NUMBER)
			->setSqltype('int(11) NOT NULL DEFAULT \'0\''));

    }
    

} 

?>
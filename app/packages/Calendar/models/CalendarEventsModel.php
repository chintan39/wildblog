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


class CalendarEventsModel extends AbstractPagesModel {
	
	var $package='Calendar';
	var $icon='calendar', $table='events';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdDateTimeFrom());
    	$this->addMetaData(AbstractAttributesModel::stdDateTimeTo());
		
		$this->addMetaData(ModelMetaItem::create('postTagsConnection')
			->setLabel('Tags')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    	
		$this->addMetaData(ModelMetaItem::create('repeat_group')
			->setLabel('Repeat group')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqltype('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex('index'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('CalendarRepeatGroupsModel', 'repeat_group', 'id'); // define a 1:many relation to Reaction 
        $this->addCustomRelationMany('CalendarTagsModel', 'CalendarEventsTagsModel', 'event', 'tag', 'postTagsConnection'); // define a many:many relation to Tag through BlogTag
    }

}

?>
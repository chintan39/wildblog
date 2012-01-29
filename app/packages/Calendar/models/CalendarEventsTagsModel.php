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


class CalendarEventsTagsModel extends AbstractDefaultModel {
	
	var $package = 'Calendar';
	var $icon = '', $table = 'events_tags';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('event')
			->setSqltype('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex('index'));

		$this->addMetaData(AtributesFactory::create('tag')
			->setSqltype('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex('index'));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('CalendarEventsModel', 'event', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation('CalendarTagsModel', 'tag', 'id'); // define a many:many relation to Tag through BlogTag
    }


} 

?>
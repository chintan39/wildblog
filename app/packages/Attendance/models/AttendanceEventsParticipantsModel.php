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


class AttendanceEventsParticipantsModel extends AbstractDefaultModel {
	
	var $package = 'Attendance';
	var $icon = '', $table = 'events_participants';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('event')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX));

		$this->addMetaData(AtributesFactory::create('participant')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('AttendanceEventsModel', 'event', 'id');
        $this->addCustomRelation('AttendanceParticipantsModel', 'participant', 'id');
    }


} 

?>

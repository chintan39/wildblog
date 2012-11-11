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


class AttendanceEventsModel extends AbstractNodesModel {

	var $package = 'Attendance';
	var $icon = 'newsletter', $table = 'events';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

		$this->addMetaData(AtributesFactory::stdText()->setSqlindex(ModelMetaIndex::FULLTEXT));

		$this->addMetaData(AtributesFactory::stdDescription()->setSqlindex(ModelMetaIndex::FULLTEXT));

		$this->addMetaData(AtributesFactory::stdDateFrom());
		
		$this->addMetaData(AtributesFactory::stdLocation());
    	
		$this->addMetaData(AtributesFactory::create('eventsParticipantsConnection')
			->setLabel('Participatns')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
    }

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('AttendanceParticipantsModel', 'AttendanceEventsParticipantsModel', 'event', 'participant', 'eventsParticipantsConnection');
    }

} 

?>

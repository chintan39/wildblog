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


class AttendanceRegistrationModel extends AbstractVirtualModel {

	var $package = 'Attendance';
	var $event = null;
    
	protected function attributesDefinition() {
		
    	$this->addMetaData(AtributesFactory::stdAccountEmail()
    		->addRestrictions(Restriction::R_NOT_EMPTY));
    	
    	$this->addMetaData(AtributesFactory::stdFirstname());
    	
    	$this->addMetaData(AtributesFactory::stdSurname());
    	
    	$this->addMetaData(AtributesFactory::stdPhone());
	}
	
	/**
	 * Save data to virtual object means creating a new DB object
	 * for participant if not already existed and connect it with
	 * the event specified in $event object attribute.
	 */
	public function Save() {
		if ($this->event == null)
			throw new Exception('Event is not specified when creating a new participant in registration model');

		$participant = AttendanceParticipantsModel::Search('AttendanceParticipantsModel', array('email = ?'), array($this->email));
		
		if (!$participant) {
			$participant = new AttendanceParticipantsModel();
			$participant->email = $this->email;
			$participant->Save();
			$participant->Connect($this->event);
		} else {
			$participant = $participant[0];
			$events = $participant->Find('AttendanceEventsModel', array('event = ?'), array($this->event->id));
			if ($events)
				MessageBus::sendMessage(tg('You are already registered for this event'), false, 'registrationForm'); 
			else
				$participant->Connect($this->event);
		}
		
		$participant->firstname = $this->firstname;
		$participant->surname = $this->surname;
		$participant->phone = $this->phone;
		$participant->Save();
	}
	
}

?>

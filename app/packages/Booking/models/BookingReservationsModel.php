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


class BookingReservationsModel extends AbstractDefaultModel {
	
	var $package = 'Booking';
	var $icon = 'booking', $table = 'reservations';
	var $languageSupportAllowed = false;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AtributesFactory::stdDateFrom()
    		->setRestrictions(Restriction::R_NOT_EMPTY));
    	
    	$this->addMetaData(AtributesFactory::stdDateTo()
    		->setRestrictions(Restriction::R_NOT_EMPTY)
    		->setIsEditable(ModelMetaItem::NEVER)
    		->setAdjustMethod('DateFromPlusNights'));

		$this->addMetaData(AtributesFactory::create('nights')
			->setLabel('Nights')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_NUMBER)
			->setSqlType('int NOT NULL'));

    	$this->addMetaData(AtributesFactory::stdPrice());
    	
		$this->addMetaData(AtributesFactory::create('currency')
			->setLabel('Currency')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));

    	$statusOptions = array();
		$statusOptions[] = array('id' => 1, 'value' => 'pending');
		$statusOptions[] = array('id' => 2, 'value' => 'confirmed');
		$statusOptions[] = array('id' => 3, 'value' => 'cancelled');

    	$this->addMetaData(AtributesFactory::create('status')
			->setLabel('Status')
			->setDescription('what status the reservation is in')
			->setType(Form::FORM_SELECT)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setOptions($statusOptions)
			->setOptionsMustBeSelected(true));
		
		$this->addMetaData(AtributesFactory::stdToken());

		$this->addMetaData(AtributesFactory::create('reservationsRoomsConnection')
			->setLabel('Rooms')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
		$this->addMetaData(AtributesFactory::create('beds')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));

		$this->addMetaData(AtributesFactory::stdIdentification());
		
		$this->addMetaData(AtributesFactory::stdInserted());
		$this->addMetaData(AtributesFactory::stdUpdated());
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BookingRoomsModel', 'BookingReservationsRoomsModel', 'reservation', 'room', 'reservationsRoomsConnection'); // define a many:many relation to Tag through BlogTag
    }
    

   	protected function sortingDefinition() {
		$this->sorting = array(new ItemSorting('updated', SORTING_DESC));
	}

	protected function adjustFunctionDateFromPlusNights(&$meta, &$newData) {
		return Utilities::dateAddDays($newData['date_from'], $newData['nights']-1);exit;
	}
} 

?>
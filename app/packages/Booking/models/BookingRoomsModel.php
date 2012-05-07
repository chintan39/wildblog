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


class BookingRoomsModel extends AbstractPagesModel {
	
	var $package = 'Booking';
	var $icon = 'bed', $table = 'rooms';
	var $languageSupportAllowed = false;
	
	const PRIVATE_ROOM = 1;
	const SHARED_ROOM = 2;

	const PRICE_ROOM = 1;
	const PRICE_BED = 2;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('capacity')
			->setLabel('Capacity')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_NUMBER)
			->setSqlType('int NOT NULL'));

		$this->addMetaData(AtributesFactory::create('identification')
			->setLabel('Identification')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));

    	$roomTypes = array();
		$roomTypes[] = array('id' => self::PRIVATE_ROOM, 'value' => 'private');
		$roomTypes[] = array('id' => self::SHARED_ROOM, 'value' => 'shared');

    	$this->addMetaData(AtributesFactory::create('roomType')
			->setLabel('Room type')
			->setDescription('what type of room it is')
			->setType(Form::FORM_SELECT)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setOptions($roomTypes)
			->setOptionsMustBeSelected(true));

    	$priceTypes = array();
		$priceTypes[] = array('id' => self::PRICE_ROOM, 'value' => 'room');
		$priceTypes[] = array('id' => self::PRICE_BED, 'value' => 'bed');

    	$this->addMetaData(AtributesFactory::create('pricingType')
			->setLabel('Pricing type')
			->setDescription('what is paid in this room')
			->setType(Form::FORM_SELECT)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setOptions($priceTypes)
			->setOptionsMustBeSelected(true));
		
		$this->addMetaData(AtributesFactory::create('pricesRoomsConnection')
			->setLabel('Prices')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BookingReservationsModel', 'BookingReservationsRoomsModel', 'room', 'reservation', 'reservationsRoomsConnection'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelationMany('BookingPricesModel', 'BookingPricesRoomsModel', 'room', 'price', 'pricesRoomsConnection'); // define a many:many relation to Tag through BlogTag
    }
} 

?>
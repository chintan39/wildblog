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


class BookingReservationsRoomsModel extends AbstractDefaultModel {
	
	var $package = 'Booking';
	var $icon = '', $table = 'reservations_rooms';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('reservation')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX));

		$this->addMetaData(AtributesFactory::create('room')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX));

		$this->addMetaData(AtributesFactory::create('beds')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('BookingReservationsModel', 'reservation', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation('BookingRoomsModel', 'room', 'id'); // define a many:many relation to Tag through BlogTag
    }

    
    static public function removeReservationRooms($reservationId) {
		$reservationsRoomsClass = new BookingReservationsRoomsModel();
		$reservationsRoomsTable = '`' . $reservationsRoomsClass->getTableName() . '`';
		
		// delete all rooms that matters in our reservations
		$query = "
			DELETE FROM $reservationsRoomsTable
			WHERE reservation = $reservationId";
		if (DEBUG_PRINT_QUERIES) print $query;
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('removeReservationRooms SQL: ' . $query); // QUERY logger
		}
		return dbConnection::getInstance()->query($query);
    } 

} 

?>
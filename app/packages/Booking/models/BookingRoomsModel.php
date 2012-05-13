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

		$this->addMetaData(AtributesFactory::stdIdentification());

    	$roomTypes = array();
		$roomTypes[] = array('id' => self::PRIVATE_ROOM, 'value' => 'private');
		$roomTypes[] = array('id' => self::SHARED_ROOM, 'value' => 'shared');

    	$this->addMetaData(AtributesFactory::create('room_type')
			->setLabel('Room type')
			->setDescription('what type of room it is')
			->setType(Form::FORM_SELECT)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setOptions($roomTypes)
			->setOptionsMustBeSelected(true));

    	$priceTypes = array();
		$priceTypes[] = array('id' => self::PRICE_ROOM, 'value' => 'room');
		$priceTypes[] = array('id' => self::PRICE_BED, 'value' => 'bed');

    	$this->addMetaData(AtributesFactory::create('pricing_type')
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
        $this->addCustomRelationMany('BookingReservationViewModel', 'BookingReservationsRoomsModel', 'room', 'reservation', 'reservationsRoomsConnection'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelationMany('BookingPricesModel', 'BookingPricesRoomsModel', 'room', 'price', 'pricesRoomsConnection'); // define a many:many relation to Tag through BlogTag
    }


	public static function getRoomInfo($room, $dateFrom, $nights, $reservationIgnoreId=false) {
		$dateLast = Utilities::dateAddDays($dateFrom, $nights-1);
		$days = Utilities::dateRangeDays($dateFrom, $nights);
		$result = array();
		
		// get last price definition before starting date
		foreach (Utilities::dateRangeDates($dateFrom, $dateLast) as $d) {
			$result[$d] = new stdClass;
			$result[$d]->price = 0;
			$result[$d]->free = true;
		}

		foreach (self::getPricesForRoomBetweenDates($room, $dateFrom, $dateLast) as $d => $price)
			$result[$d]->price = $price;
		
		foreach (self::getFreeBedsForRoomBetweenDates($room, $dateFrom, $dateLast, $reservationIgnoreId) as $d => $free)
			$result[$d]->free = $free;
		
		return $result;
	}


	public static function getPricesForRoomBetweenDates($room, $dateFirst, $dateLast) {
		$pricesClass = new BookingPricesModel();
		$pricesRoomsClass = new BookingPricesRoomsModel();
		$pricesTable = '`' . $pricesClass->getTableName() . '`';
		$pricesRoomsTable = '`' . $pricesRoomsClass->getTableName() . '`';
		
		$roomId = $room->id;
		
		// get all other prices that matters in our term
		$query = "
			SELECT prices.date_from, prices.price
			FROM $pricesRoomsTable AS prices_rooms
			LEFT JOIN $pricesTable AS prices ON prices_rooms.price = prices.id
			WHERE prices_rooms.room = $roomId
			AND prices.date_from <= '$dateLast'
			ORDER BY prices.date_from ASC";
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('getPricesForRoomBetweenDates SQL: ' . $query); // QUERY logger
		}
		if (DEBUG_PRINT_QUERIES) print $query;
		$prices = dbConnection::getInstance()->fetchAll($query);
		$result = array();
		if ($prices) {
			foreach ($prices as $p) {
				foreach (Utilities::dateRangeDates($p['date_from'] < $dateFirst ? $dateFirst : $p['date_from'], $dateLast) as $d)
					$result[$d] = $p['price'];
			}
		}
		return $result;
	}


	public static function getFreeBedsForRoomBetweenDates($room, $dateFirst, $dateLast, $reservationIgnoreId=false) {
		$reservationsClass = new BookingReservationsModel();
		$reservationsRoomsClass = new BookingReservationsRoomsModel();
		$reservationsTable = '`' . $reservationsClass->getTableName() . '`';
		$reservationsRoomsTable = '`' . $reservationsRoomsClass->getTableName() . '`';
		
		$roomId = $room->id;
		
		$result = array();
		foreach (Utilities::dateRangeDates($dateFirst, $dateLast) as $d)
			$result[$d] = $room->capacity;

		$reservationIgnoreSQL = $reservationIgnoreId ? "and reservations_rooms.reservation <> $reservationIgnoreId" : '';
		
		// get all other prices that matters in our term
		$query = "
			SELECT reservations.id, reservations.date_from, reservations.date_to, reservations.nights, reservations_rooms.beds
			FROM $reservationsRoomsTable AS reservations_rooms
			LEFT JOIN $reservationsTable AS reservations ON reservations_rooms.reservation = reservations.id
			WHERE reservations_rooms.room = $roomId $reservationIgnoreSQL
			AND ((reservations.date_to >= '$dateFirst' AND reservations.date_to <= '$dateLast')
				OR (reservations.date_from >= '$dateFirst' AND reservations.date_from <= '$dateLast')
				OR (reservations.date_from <= '$dateFirst' AND reservations.date_from >= '$dateLast'))
			ORDER BY reservations.date_from ASC";
		if (DEBUG_PRINT_QUERIES) print $query;
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('getPricesForRoomBetweenDates SQL: ' . $query); // QUERY logger
		}
		$reservations = dbConnection::getInstance()->fetchAll($query);

		if ($reservations) {
			foreach ($reservations as $r) {
				foreach (Utilities::dateRangeDates($r['date_from'] < $dateFirst ? $dateFirst : $r['date_from'], 
					$r['date_to'] > $dateLast ? $dateLast : $r['date_to']) as $d) {
						if ($room->room_type == self::PRIVATE_ROOM && $r['beds'])
							$result[$d] = 0;
						else
							$result[$d] = $room->capacity - $r['beds'];
					}
			}
		}
		return $result;
	}
	
	
	public static function getReservationRoomBeds($reservationId) {
		$reservationsRoomsClass = new BookingReservationsRoomsModel();
		$reservationsRoomsTable = '`' . $reservationsRoomsClass->getTableName() . '`';
		
		$result = array();

		// get all other prices that matters in our term
		$query = "
			SELECT reservations_rooms.room, reservations_rooms.beds
			FROM $reservationsRoomsTable AS reservations_rooms
			WHERE reservations_rooms.reservation = $reservationId";
		if (DEBUG_PRINT_QUERIES) print $query;
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('getReservationRoomBeds SQL: ' . $query); // QUERY logger
		}
		$rooms = dbConnection::getInstance()->fetchAll($query);

		if ($rooms) {
			foreach ($rooms as $r) {
				$result['room'.$r['room']] = $r['beds'];
			}
		}
		return $result;
	}
	
} 

?>
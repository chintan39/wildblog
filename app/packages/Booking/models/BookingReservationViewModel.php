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


class BookingReservationViewModel extends BookingReservationFormModel {
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('beds')
			->setLabel('Beds')
			->setDescription('sum of beds booked')
			->setType(Form::FORM_INPUT_NUMBER)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setFormStepsOptions(array(ModelMetaItem::STEP_READONLY, ModelMetaItem::STEP_EDITABLE)));

		$this->getMetaData('date_from')
			->setFormStepsOptions(array(ModelMetaItem::STEP_READONLY, ModelMetaItem::STEP_EDITABLE));
		$this->getMetaData('nights')
			->setFormStepsOptions(array(ModelMetaItem::STEP_READONLY, ModelMetaItem::STEP_EDITABLE));
		$this->getMetaData('price')
			->setFormStepsOptions(array(ModelMetaItem::STEP_READONLY, ModelMetaItem::STEP_EDITABLE));
		$this->getMetaData('currency')
			->setFormStepsOptions(array(ModelMetaItem::STEP_READONLY, ModelMetaItem::STEP_EDITABLE));
		foreach ($this->rooms as $room)
			$this->getMetaData('room' . $room->id)
				->setFormStepsOptions(array(ModelMetaItem::STEP_READONLY, ModelMetaItem::STEP_EDITABLE));
    }
    
    
	/** 
	 *	Fills the current object with the corresponding row from the database. 
	 */
	protected function __init() 
	{
		if ($this->id != false) {
			$reservation = new BookingReservationsModel($this->id);
			$this->date_from = $reservation->date_from;
			$this->nights = $reservation->nights;
			$this->price = $reservation->price;
			$this->currency = $reservation->currency;
			$this->beds = $reservation->beds;
			
			foreach (BookingRoomsModel::getReservationRoomBeds($this->id) as $roomId => $beds) {
				if ($this->hasMetadata($roomId))
					$this->$roomId = $beds;
			}
		}
	}
    
} 

?>
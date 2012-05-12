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


class BookingReservationFormModel extends AbstractVirtualModel {
	
	var $package = 'Booking';
	var $icon = 'booking';
	var $languageSupportAllowed = false;
	var $rooms = array();

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	$this->addMetaData(AtributesFactory::stdId());
    	
    	$this->addMetaData(AtributesFactory::stdDateFrom()
    		->setRestrictions(Restriction::R_NOT_EMPTY)
    		->setDefaultValue(date('Y-m-d'))
    		->setFormStepsOptions(array(ModelMetaItem::STEP_EDITABLE, ModelMetaItem::STEP_READONLY, ModelMetaItem::STEP_READONLY)));
    	
		$this->addMetaData(AtributesFactory::create('nights')
			->setLabel('Nights')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_NUMBER)
    		->setDefaultValue(3)
    		->setFormStepsOptions(array(ModelMetaItem::STEP_EDITABLE, ModelMetaItem::STEP_READONLY, ModelMetaItem::STEP_READONLY)));

    	$this->addMetaData(AtributesFactory::stdPrice()
    		->setFormStepsOptions(array(ModelMetaItem::STEP_HIDDEN, ModelMetaItem::STEP_HIDDEN, ModelMetaItem::STEP_READONLY)));
    	
		/*$this->addMetaData(AtributesFactory::create('currency')
			->setLabel('Currency')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
		*/

    }
    

	/**
	 * Save data to Reservations model.
	 */
	public function Save() {
		parent::Save();
		$reservation = new BookingReservationsModel();
		$reservation->date_from = $this->date_from;
		$reservation->date_to = Utilities::dateAddDays($this->date_from, $this->nights-1);
		$reservation->nights = $this->nights;
		$reservation->beds = 0;
		$reservation->Save();
		foreach ($this->rooms as $room) {
			$roomId = 'room' . $room->id;
			if ($this->$roomId) {
				$conn = new BookingReservationsRoomsModel();
				$conn->room = $room->id;
				$conn->reservation = $reservation->id;
				$conn->beds = $this->$roomId;
				$reservation->beds += $this->$roomId;
				$conn->Save();
			}
		}
		$reservation->Save();
	}
	
	
    public function addRoom($room) {
    	
    	$this->addMetaData(AtributesFactory::create('room'.$room->id)
			->setLabel($room->title)
			->setDescription($room->text . '(' . (($room->room_type == BookingRoomsModel::PRIVATE_ROOM) ? tg('private') : tg('shared')) . ')')
			->setType(Form::FORM_CUSTOM)
			->setRenderObject($this)
			->setOptionsMustBeSelected(true)
			->setUpdateHandleDefault(true)
    		->setFormStepsOptions(array(ModelMetaItem::STEP_HIDDEN, ModelMetaItem::STEP_EDITABLE, ModelMetaItem::STEP_READONLY)));
		
		$this->rooms['room'.$room->id] = $room;
    }
    
    
	public function getFormHTMLReadonly($formField) {
		$meta = $formField->getMeta();
		$model = $formField->getDataModel();
		$fieldName = $meta->getName();
		if (strncmp($fieldName, 'room', 4) == 0) {
			// don't display empty rooms
			if (!$formField->getValue()) {
				$formField->removeBox();
				$formField->removeLabel();
				return '';
			}
			
			$output = '';
			$room = $this->rooms[$fieldName];
	
			$roomInfo = BookingRoomsModel::getRoomInfo($room, $model->date_from, $model->nights);
			$minFree = $room->capacity;
			foreach ($roomInfo as $i)
				if ($i->free < $minFree)
					$minFree = $i->free;
			if ($room->priceType == BookingRoomsModel::PRICE_ROOM)
				$roomBeds = array(0, $minFree);
			else
				$roomBeds = range(0, $minFree);
	
	
			$output .= '<table class="prices" style="width: 70%;"><tr>'."\n";
			foreach ($roomInfo as $date => $info) {
				$output .= '<th>'.$date.'</th>'."\n";
			}
			$output .= '</tr><tr>'."\n";
			foreach ($roomInfo as $date => $info) {
				if ($info->free)
					$output .= '<td class="free">'.Utilities::formatPrice($info->price).'</td>'."\n";
				else
					$output .= '<td class="full">'.tg('Full').'</td>'."\n";
			}
			$output .= '</tr></table>'."\n";
			$output .= '<div class="note">' . $formField->getValue() . ' ' . ($formField->getValue() > 1 ? tg('Beds') : tg('Bed')) . "</div>\n";
			return $output;
		}
	}

	public function getFormHTMLEditable($formField) {
		$meta = $formField->getMeta();
		$model = $formField->getDataModel();
		$fieldName = $meta->getName();
		if (strncmp($fieldName, 'room', 4) == 0) {
			$output = '';
			$room = $this->rooms[$fieldName];
	
			$roomInfo = BookingRoomsModel::getRoomInfo($room, $model->date_from, $model->nights);
			$minFree = $room->capacity;
			foreach ($roomInfo as $i)
				if ($i->free < $minFree)
					$minFree = $i->free;
			if ($room->priceType == BookingRoomsModel::PRICE_ROOM)
				$roomBeds = array(0, $minFree);
			else
				$roomBeds = range(0, $minFree);
	
	
			$output .= '<table class="prices" style="width: 70%;"><tr>'."\n";
			foreach ($roomInfo as $date => $info) {
				$output .= '<th>'.$date.'</th>'."\n";
			}
			$output .= '</tr><tr>'."\n";
			foreach ($roomInfo as $date => $info) {
				if ($info->free)
					$output .= '<td class="free">'.Utilities::formatPrice($info->price).'</td>'."\n";
				else
					$output .= '<td class="full">'.tg('Full').'</td>'."\n";
			}
			$output .= '</tr></table>'."\n";
			$output .= '<div class="note">'."\n";
			$output .= '<select '.$formField->getIdAttr().' name="' . $fieldName . '" class="short">';
				foreach ($roomBeds as $bedCount) {
					$output .= '<option value="' . $bedCount . '"'. ($formField->getValue() && ($formField->getValue() == $bedCount) ? ' selected="selected"' : ''). '>' . $bedCount . '</option>'."\n";
			}
	
			$output .= '</select>'."\n";
			$output .= '<span class="note short">' . tg('Beds') . "</span>\n";
			$output .= '</div>' . "\n";
			return $output;
		}
	}


	/**
	 * Adjusts values of the fields, checks field's format and value.
	 * @param &$newData
	 * @return array List of messages (errors and warnings).
	 */
	public function checkFields(&$newData, &$preddefinedData, $formStep) {
		parent::checkFields($newData, $preddefinedData, $formStep);
		$beds = 0;
		$checkBeds = false;
		foreach ($this->rooms as $room) {
			$roomId = 'room' . $room->id;
			$stepOptions = $this->getMetaData($roomId)->getFormStepsOptions();
			$checkBeds |= (isset($stepOptions[$formStep-1]) && $stepOptions[$formStep-1] == ModelMetaItem::STEP_EDITABLE);
			$roomBeds = $newData[$roomId];
			// check if rooms have valid beds selected
			if ($roomBeds > $room->capacity) {
				$this->addMessageField('errors', $this->getMetaData($roomId), tg('Number of beds in room is not valid')); 
			}
			$beds += $roomBeds;
		}
		
		// check if at least some beds are selected
		if ($checkBeds && !$beds)
			$this->addMessageSimple('errors', tg('You have to select at least some beds'));
		
		return $this->messages;
	}
} 

?>
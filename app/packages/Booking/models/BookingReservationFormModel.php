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
    		->setDefaultValue(date('Y-m-d')));
    	
		$this->addMetaData(AtributesFactory::create('nights')
			->setLabel('Nights')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_NUMBER)
    		->setDefaultValue(3));

    	$this->addMetaData(AtributesFactory::stdPrice());
    	
		/*$this->addMetaData(AtributesFactory::create('currency')
			->setLabel('Currency')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
		*/

    }
    

    public function addRoom($room) {
    	
    	$this->addMetaData(AtributesFactory::create('room'.$room->id)
			->setLabel($room->title)
			->setDescription($room->text)
			->setType(Form::FORM_CUSTOM)
			->setRenderObject($this)
			->setOptionsMustBeSelected(true));
		
		$this->rooms['room'.$room->id] = $room;
    }
    
    
	public function getFormHTML($formField) {
		// TODO: move function to utilities
		require_once(DIR_SMARTY_WWPLUGINS . 'modifier.price.php');
		
		$meta = $formField->getMeta();
		$model = $formField->getDataModel();
		$fieldName = $meta->getName();
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
				$output .= '<td class="free">'.smarty_modifier_price($info->price).'</td>'."\n";
			else
				$output .= '<td class="full">'.tg('Full').'</td>'."\n";
		}
		$output .= '</tr></table>'."\n";
		$output .= '<select '.$formField->getIdAttr().' name="' . $fieldName . '">';
			foreach ($roomBeds as $bedCount) {
				$output .= '<option value="' . $bedCount . '"'. ($model->$fieldName && ($model->$fieldName == $bedCount) ? ' selected="selected"' : ''). '>' . $bedCount . '</option>'."\n";
		}

		$output .= '</select>'."\n";
		return $output;
	}
} 

?>
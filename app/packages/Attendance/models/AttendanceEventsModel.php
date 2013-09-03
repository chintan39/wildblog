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
    	
//		$this->addMetaData(AtributesFactory::create('eventsParticipantsConnection')
//			->setLabel('Participatns')
//			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
//			->setOptionsMethod('listSelect'));
    	$this->addMetaData(AtributesFactory::create('eventsParticipantsConnection')
    		->setLabel('Participatns')
			->setDescription('add or remove participants')
			->setType(Form::FORM_SPECIFIC_NOT_IN_DB)
			->setRenderObject($this)
			->setForceIsInDb(false));
		
		$this->addMetaData(AtributesFactory::create('capacity')
			->setLabel('Capacity')
			->setDescription('maximum number of attendies')
			->setType(Form::FORM_INPUT_NUMBER)
			->setDefaultValue(25)
			->setSqlType('decimal(8,0) NULL DEFAULT NULL')
			->setAdjustMethod('Number'));

    }

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('AttendanceParticipantsModel', 'AttendanceEventsParticipantsModel', 'event', 'participant', 'eventsParticipantsConnection');
    }

    public function renderParticipant($items, &$event) {
    	if (!$event->id)
    		return '';
    	$output = '';
   		$output .= '<div class="menuLinkWrap">'."\n";
		$output .= '<div class="menuLink">'."\n";
		$output .= '<a href="'.Request::getLinkItem('Attendance', 'Events', 'actionRemoveAllParticipants', $event, array('token' => Request::$tokenCurrent)).'" onclick="return confirm(\''.tg('Are you sure to remove all participants?').'\');"><img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/remove.png" alt="'.tg('Remove all participants').'" title="'.tg('Remove all participants').'" /> '.tg('Remove all participants').'</a>'."\n";
		$output .= '</div> <!-- div.menuLink -->'."\n";
    	foreach ($items as $item) {
    		$connectItem = AttendanceEventsParticipantsModel::Search('AttendanceEventsParticipantsModel', array('event = ?', 'participant = ?'), array($event->id, $item->id));
    		$connectItem = $connectItem[0];
    		$output .= '<div class="menuLink">'."\n";
    		$output .= '<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/'.$item->getIcon().'.png" class="menuItemIcon" alt="'.$item->id.'" title="'.tg('Item').' #'.$item->id.'" />'."\n";
    		$output .= '<div class="menuLinkTitleWrap">'."\n";
    		$output .= '<div class="menuLinkTitle">'.$item->makeSelectTitle()."</div>\n";
    		$output .= '</div> <!-- div.menuLinkTitleWrap -->'."\n";
    		$output .= '<div class="clear"></div>'."\n";
    		$output .= '<div class="menuLinkIcons">'."\n";
    		$output .= '<a href="'.Request::getLinkItem('Attendance', 'EventsParticipants', 'actionRemoveParticipant', $connectItem, array('token' => Request::$tokenCurrent)).'" onclick="return confirm(\''.tg('Are you sure to remove this item?').'\');"><img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/remove.png" alt="'.tg('Remove').'" title="'.tg('Remove item').'" /></a>'."\n";
    		$output .= '</div> <!-- div.menuLinkIcons -->'."\n";
    		$output .= '</div> <!-- div.menuLink -->'."\n";
		}
   		$output .= '</div><!-- div.menuLinkWrap -->'."\n";
    	return $output;
    }

	public function getFormHTML($formField) {
		$meta = $formField->getMeta();
		$model = $formField->getDataModel();
		$fieldName = $meta->getName();
		$output = '';
		if ($fieldName == 'eventsParticipantsConnection') {
			$output .= $this->renderParticipant($model->Find('AttendanceParticipantsModel'), $model);
		}
		return $output;
	}
} 

?>

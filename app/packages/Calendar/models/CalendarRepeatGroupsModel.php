<?php

class CalendarRepeatGroupsModel extends AbstractDefaultModel {
	
	var $package = 'Calendar';
	var $icon = 'tag', $table = 'repeat_groups';
    
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$periodOptions = array(
			array('id' => 0, 'value' => 'no repeat'),
			array('id' => 1, 'value' => 'days'),
			array('id' => 2, 'value' => 'weeks'),
			array('id' => 3, 'value' => 'months'),
			array('id' => 4, 'value' => 'years'),
			);
		
		$this->addMetaData(ModelMetaItem::create('repeat_period')
			->setLabel('repeat_period')
			->setType(Form::FORM_SELECT)
			->setOptions($periodOptions)
			->setSqltype('int(11) NOT NULL DEFAULT \'0\''));

		$this->addMetaData(ModelMetaItem::create('repeat_times')
			->setLabel('repeat_times')
			->setType(Form::FORM_INPUT_NUMBER)
			->setSqltype('int(11) NOT NULL DEFAULT \'0\''));

    }
    

} 

?>
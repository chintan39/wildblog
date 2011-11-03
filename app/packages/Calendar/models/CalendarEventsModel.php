<?php

class CalendarEventsModel extends AbstractPagesModel {
	
	var $package='Calendar';
	var $icon='calendar', $table='events';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdDateTimeFrom());
    	$this->addMetaData(AbstractAttributesModel::stdDateTimeTo());
		
		$this->addMetaData(ModelMetaItem::create('postTagsConnection')
			->setLabel('Tags')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    	
		$this->addMetaData(ModelMetaItem::create('repeat_group')
			->setLabel('Repeat group')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqltype('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex('index'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('CalendarRepeatGroupsModel', 'repeat_group', 'id'); // define a 1:many relation to Reaction 
        $this->addCustomRelationMany('CalendarTagsModel', 'CalendarEventsTagsModel', 'event', 'tag', 'postTagsConnection'); // define a many:many relation to Tag through BlogTag
    }

}

?>
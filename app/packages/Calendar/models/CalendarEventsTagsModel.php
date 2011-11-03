<?php

class CalendarEventsTagsModel extends AbstractDefaultModel {
	
	var $package = 'Calendar';
	var $icon = '', $table = 'events_tags';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('event')
			->setSqltype('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex('index'));

		$this->addMetaData(ModelMetaItem::create('tag')
			->setSqltype('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex('index'));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('CalendarEventsModel', 'event', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation('CalendarTagsModel', 'tag', 'id'); // define a many:many relation to Tag through BlogTag
    }


} 

?>
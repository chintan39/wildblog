<?php

class CalendarTagsModel extends AbstractCodebookModel {
	
	var $package = 'Calendar';
	var $icon = 'tag', $table = 'tags';

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('CalendarEventsModel', 'CalendarEventsTagsModel', 'tag', 'event'); // define a many:many relation to Tag through BlogTag
    }
    

} 

?>
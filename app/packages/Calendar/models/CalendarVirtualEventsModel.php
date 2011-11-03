<?php

class CalendarVirtualEventsModel extends AbstractPagesModel {
	
	var $package = 'Calendar';
	var $icon = 'calendar';
	
	var $event = null;
	var $repeat = null;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

    	$dummy = new CalendarEventsModel();
    	
    	foreach($dummy->getMetaData() as $key => $meta) {
    		if ($key != 'repeat_group' && !array_key_exists($key, $this->getMetaData())) {
    			$this->addMetaData($key, $meta);
    		}
    	}
    	
    	$dummy = new CalendarRepeatGroupsModel();
    	
    	foreach($dummy->getMetaData() as $key => $meta) {
    		if (!array_key_exists($key, $this->getMetaData())) {
    			$this->addMetaData($key, $meta);
    		}
    	}
    	
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('CalendarTagsModel', 'CalendarEventsTagsModel', 'event', 'tag'); // define a many:many relation to Tag through BlogTag
    }

    
	public function Save($forceSaving=false) 
	{
		if (!$this->repeat_times) {
			if (!$this->event) {
				$this->event = new CalendarEventsModel();
			}
			/*
			foreach($this->event->metaData as $key => $meta) {
				if ($key != 'id' && $key != 'postTagsConnection' && $key != 'repeat_group') {
					$this->event->$key = $this->$key;
				}
			}
		} else {
			if ($this->repeat === null) {
				$this->repeat = new CalendarRepeatGroupsModel();
			}
			$this->repeat->repeat_period = $this->repeat_period;
			$this->repeat->repeat_times = $this->repeat_times;
			
			// inserting repeated events - only if we are inserting the event
			// (not when we update it)
			if (!$this->id) {
				for ($i = 0; $i < $this->repeat_times; $i++) {
					$this->event = new CalendarEventsModel();
					foreach($this->event->metaData as $key => $meta) {
						if ($key != 'postTagsConnection' && $key != 'repeat_group') {
							$this->event->$key = $this->$key;
						}
					}
					$this->event->Connect($repeat);
				}
			}*/
		}
		
		$this->id = $this->event->id;
		$this->event->Save($forceSaving);
	}
	
	public function Connect($object) {
		if ($this->event === null) {
			$this->event = new CalendarEventsModel();
		}
		$this->event->Connect($object);
	} 


	public function Disconnect($object, $id=false) {
		if ($this->event === null) {
			$this->event = new CalendarEventsModel();
		}
		$this->event->Disconnect($object, $id);
	}
	
	/*
	public function __get($property) {
		try {
			return $this->event->$property;
		} catch (Exception $e) {
			return $this->repeat->$property;
		}
	}

	public function __set($property, $value) {
		try {
			$this->event->$property = $value;
		} catch (Exception $e) {
			$this->repeat->$property = $value;
		}
	}
	*/
}

?>

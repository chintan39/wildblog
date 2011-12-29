<?php

class BasicNamesController extends AbstractBasicController {
	
	/**
	 * Request handler
	 * Name days controller 
	 */
	public function subactionGetName($args) {
		Benchmark::log("Begin of creating BasicNamesController::subactionGetName");
		require_once(DIR_LIBS . 'namedays/NameDays.class.php');
		NameDays::init(Language::getCode());
		$todayName = NameDays::getName();
		$todayEvent = NameDays::getToleranceFlowerDays(14);
		$this->assign("todayName", $todayName);
		$this->assign("todayEvent", $todayEvent);
		Benchmark::log("End of creating BasicNamesController::subactionGetName");
	}


}

?>

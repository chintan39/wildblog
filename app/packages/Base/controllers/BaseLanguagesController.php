<?php

class BaseLanguagesController extends AbstractNodesController {
	
	public $order = 7;				// order of the controller (0-10)
	
	public function translate($text) {
		return $text;
	}
	
}


?>

<?php
/**
 * Package covering link building actions and tools.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class DefaultAbstractPackage extends Package {
    
	public function loadModels() {
		$this->requireFilesWithExtention($this->packageDirectory . DIR_MODELS, "php");
	}
	
	/**
	 * Reads all controllers directory and includes all controllers.
	 * Controlleres are stored in the array.
	 */
	public function loadControllers() {
		// load system controllers
		$this->requireFilesWithExtention($this->packageDirectory . DIR_CONTROLLERS, "php");
	}
	
}

?>
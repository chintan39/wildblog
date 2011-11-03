<?php
/**
 * Package covering link building actions and tools.
 * @var <string> icon name of the main icon in the package (will be used in menu
 * and so on.
 */
class AbstractProductionPackage extends DefaultAbstractPackage {
    	
	public function setDefaultConfig() {
		
		Config::Set("ABSTRACTPRODUCTION_PACKAGE_ORDER", 10, null, Config::INT, true);
		Config::Set("ABSTRACTPRODUCTION_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("ABSTRACTPRODUCTION_PACKAGE_ALLOW", true, null, Config::BOOL, false);
	
	}

}

?>
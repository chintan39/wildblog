<?php

class AbstractPackage extends DefaultAbstractPackage {
		
	public function setDefaultConfig() {
		
		Config::Set("ABSTRACT_PACKAGE_ORDER", 10, 'Abstract package order', Config::INT, false);
		Config::Set("ABSTRACT_PACKAGE_LANGUAGE_SUPPORT", false, 'Abstract package language support', Config::BOOL, false);
		Config::Set("ABSTRACT_PACKAGE_ALLOW", true, 'Abstract package allow', Config::BOOL, false);
	
	}
}

?>
<?php

class BaseImplicitModel extends AbstractVirtualModel {

	var $user = false;
	var $package = 'Base';
	
    function __construct($id = false)
    {
    	parent::__construct($id);
	}
	

}

?>

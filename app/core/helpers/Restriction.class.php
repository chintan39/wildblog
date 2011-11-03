<?php

/** 
 * All available restrictions applicable to data in any model.
 * Database constraints are created from that restrictions.
 */
class Restriction {
	
	const R_UNIQUE 				= 0x00000001;
	const R_URL_PART 			= 0x00000002;
	const R_NUMBER 				= 0x00000004;
	const R_DATE 				= 0x00000008;
	const R_TIME 				= 0x00000010;
	const R_TIMESTAMP 			= 0x00000020;
	const R_INDEX 				= 0x00000040;
	const R_PRICE 				= 0x00000080;
	const R_EMAIL 				= 0x00000100;
	const R_URL 				= 0x00000200;
	const R_PRIMARY 			= 0x00000400;
	const R_BOOL 				= 0x00000800;
	const R_NO_EDIT_ON_EMPTY	= 0x00001000;
	const R_CONFIRM_DOUBLE		= 0x00002000;
	const R_NOT_EMPTY			= 0x00004000;
	const R_TEXT				= 0x00008000;
	const R_EMPTY				= 0x00010000;
	const R_SHA1				= 0x00020000;
	const R_LINK				= 0x00040000;
	const R_COLOR_RGBHEXA		= 0x00080000;
	
	const R_CONFIRM_PREFIX = "confirm_";
	
	
	/**
	 * Checks if the subject has restrictions for some think.
	 * @return bool True if has, False if has not.
	 */
	public static function hasRestrictions($subject, $restrictions) {
		return ($subject & $restrictions);
	}
}


?>

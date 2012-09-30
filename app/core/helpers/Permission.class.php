<?php
/*
    Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * Class handles user's permissions.
 */

require_once(DIR_PACKAGES . 'Base' . DIRECTORY_SEPARATOR . DIR_MODELS . 'BaseUsersModel.php');

class Permission {
		
	public static $ADMIN, $CONTENT_ADMIN, $VISITOR, $REGISTERED_VISITOR, $ALL;

	static private $permissionLevel, $actualUserId, $userInfo=false;
	
	
	/**
	 * Constructor.
	 */
	public function __construct() {
		/* Constants defined using this way becouse of combine the basic values to make new specific values */
		self::$ADMIN = 1;
		self::$VISITOR = 2;
		self::$REGISTERED_VISITOR = 4;
		self::$CONTENT_ADMIN = 8;
		self::$ALL = self::$ADMIN | self::$VISITOR | self::$REGISTERED_VISITOR | self::$CONTENT_ADMIN;
		
		self::$actualUserId = (isset($_SESSION['actualUserId'])) ? $_SESSION['actualUserId'] : false;
		self::$permissionLevel = (self::$actualUserId) ? self::getUserPermissions(self::$actualUserId) : self::$VISITOR;
		self::assign();
		self::assignRoles();
	}
	
	
	/**
	 * Actual user permission checker
	 * @param int $permissionShouldHave permission level, that must be passed
	 * @param int $permissionHaving that object has, if 
	 */
	static public function check($permissionShouldHave, $permissionHaving=null) {
		if ($permissionHaving === null) {
			return (self::$permissionLevel & $permissionShouldHave); 
		} else {
			return ($permissionHaving & $permissionShouldHave);
		}
	}
	
	
	/**
	 * Actual user ID to save
	 * @param int $user user ID, false if logout
	 */
	static public function setUser($user) {
		self::$actualUserId = $user;
		self::setSession();
		self::assign();
	}
	
	
	/**
	 * Actual user permission level getter
	 * @return int permission level
	 */
	static public function getLevel() {
		return self::$permissionLevel;
	}
	
	
	/**
	 * Sets actual permission level to session.
	 */
	static private function setSession() {
		$_SESSION['actualUserId'] = self::$actualUserId;
	}
	
	
	/**
	 * Sets actual permission level to session.
	 */
	static public function clearSession() {
		unset($_SESSION['actualUserId']);
		unset($_SESSION['timeout_idle']);
		Request::redirect(Request::getLinkSimple('Base', 'Users', 'actionLogin'));
	}
	
	
	static public function refreshSession() {
		// set session's cookie lifetime to 30min
		if (!isset($_SESSION['timeout_idle'])) {
//			throw new Exception("timeout_idle session value not defined");
			$_SESSION['timeout_idle'] = time() + Config::Get('SESSION_TIMEOUT');
		} else {
			if ($_SESSION['timeout_idle'] < time()) {   
				//destroy session
				self::clearSession();
			} else {
				$_SESSION['timeout_idle'] = time() + Config::Get('SESSION_TIMEOUT');
			}
		}
	}
	
	
	/**
	 * Assign variables to the Smarty template engine to be accessible 
	 * in the templates.
	 */
	static private function assign() {
		Environment::$smarty->assign('actualUserId', self::getActualUserId());
		Environment::$smarty->assign('actualUserInfo', self::getActualUserInfo());
		Environment::$smarty->assign('actualUserPermissionLevel', self::getActualUserPermissionLevel());
	}
	
	
	/**
	 * Assign all roles to the Smarty template engine to be accessible 
	 * in the templates.
	 * TODO: Is this realy needed?
	 */
	static private function assignRoles() {
		Environment::$smarty->assign('PERMISSION_ADMIN', self::$ADMIN);
		Environment::$smarty->assign('PERMISSION_VISITOR', self::$VISITOR);
		Environment::$smarty->assign('PERMISSION_REGISTERED_VISITOR', self::$REGISTERED_VISITOR);
	}
	
	
	/**
	 * Load info about actual logged user, if actual user's info is not loaded yet.  
	 * Then returns user's permissions.
	 $ @param int $userId ID of the user
	 * @return User's permissions.
	 */
	static private function getUserPermissions($userId) {
		if (self::$userInfo === false) {
			self::$userInfo = new BaseUsersModel($userId);
			if (!self::$userInfo) {
				throw new Exception("User with ID $userId has not been found, but ID is set in session."); 
			}
		}
		return (self::$userInfo && self::$userInfo->permissions) ? (int)self::$userInfo->permissions : self::$VISITOR;
	}
	
	
	/**
	 * Returns true if any type of user is logged
	 */
	static public function userIsLogged() {
		return (self::$actualUserId !== false);
	}
	
	
	/**
	 * Returns actual user's info
	 */
	static public function getActualUserInfo() {
		return (self::userIsLogged()) ? self::$userInfo : false;
	}

	
	/**
	 * Returns unsigned actual user's ID from session
	 */
	static private function getUserIdFromSession() {
		if (!isset($_SESSION['actualUserHashId'])) {
			$_SESSION['actualUserHashId'] = Utilities::generatePassword();
		}
		return $_SESSION['actualUserHashId'];
	}
	
	
	/**
	 * Returns actual user's ID
	 */
	static public function getActualUserId() {
		return (self::userIsLogged()) ? self::$actualUserId : self::getUserIdFromSession();
	}

	
	/**
	 * Returns actual user's permission level (visitor, admin, ...)
	 */
	static public function getActualUserPermissionLevel() {
		return (self::userIsLogged()) ? self::$permissionLevel : false;
	}
}

?>

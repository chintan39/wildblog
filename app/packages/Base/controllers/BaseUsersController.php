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


class BaseUsersController extends AbstractDefaultController {
	
	public $order = 6;				// order of the controller (0-10)
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}
	

	public static function hasAccess($role) {
        
    }
	
	/**
	 * Login Form
	 */
	public function actionLogin($args) {
		$item = new BaseLoginModel();
		$form = new Form();
		$form->fill($item);
		$form->setDescription($this->getFormDescription());
		// handeling the form request
		list($mode, $home) = explode('|', Config::Get('HOMEPAGE_ACTION_ADMIN'));
		$sessionLink = isset($_SESSION['login_redirect']) ? $_SESSION['login_redirect'] : '';
		$ha = explode('::', ($mode == 'auto' && isset($_SESSION['login_redirect']) && $_SESSION['login_redirect']) ? $_SESSION['login_redirect']: $home);
		$item = null;
		if (isset($ha[3]) && is_string($ha[3])) {
			$modelName = Environment::getPackage($ha[0])->getController($ha[1])->getModel();
			$model = new $modelName();
			$item = $model->Find($modelName, explode(',', $ha[3]), explode(',', $ha[4]));
			$item = $item ? $item[0] : false;
		}
		// TODO: Add project-defined additional locations after login.
		unset($_SESSION['login_redirect']);
		$form->handleRequest(array('all' => array(
			'package' => $ha[0], 
			'controller' => $ha[1], 
			'action' => $ha[2],
			'item' => $item)), tg('You have been logged successfully.'));
		$this->assign($form->getIdentifier(), $form->toArray());
		$_SESSION['login_redirect'] = $sessionLink;
		//$this->display('login', Themes::BACK_END);
	}


	/**
	 * Logout action
	 */
	public function actionLogout($args) {
		Permission::setUser(false);
		Request::redirect(Request::getLinkItem('Base', 'Users', 'actionLogin', 'You have been logged out.'));
	}

	
	/**
	 * Try to login using username (email) and password (will be hashed using MD5).
	 * @param string $email email of the user (username)
	 * @param string $password password of the user (will be hashed using MD5)
	 * @return object Returns data entry if found, false if not found.
	 */
	public function tryLogin($email, $password) {
		$data = new $this->model();
		$item = $data->Find($this->model, array('email = ?', 'password = ?'), array($email, $password));
		return $item;
	}


	/**
	 * Try to find an email.
	 * @param string $email email of the user (username)
	 * @return object Returns true if $email exists.
	 */
	public function emailExists($email) {
		$data = new $this->model();
		$item = (int)$data->FindCount($this->model, array('email = ?'), array($email));
		return ($item > 0);
	}
	
	
	public function actionEditProfile($args) {
		$user = Permission::getActualUserInfo();
		$user->setMetadata('permissions', 'isEditable', ModelMetaItem::NEVER);
		$user->setMetadata('last_logged', 'isEditable', ModelMetaItem::NEVER);
		$user->setMetadata('active', 'isEditable', ModelMetaItem::NEVER);
		$user->setMetadata('private_config', 'isEditable', ModelMetaItem::NEVER);
		$form = new Form();
		$form->setIdentifier(tg('Edit your profile'));
		$form->fill($user);
		// handeling the form request
		$form->handleRequest();
		$this->assign('form', $form->toArray());

		$this->assign('title', tg('Edit profile'));
	}
	
}

?>
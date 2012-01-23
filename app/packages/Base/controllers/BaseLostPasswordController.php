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


class BaseLostPasswordController extends AbstractDefaultController {
	
	public $order = 6;				// order of the controller (0-10)
	
	/**
	 * Lost Password
	 */
	public function actionLostPassword($args) {
		$item = new BaseLostPasswordFormModel();
		$form = new Form();
		$form->fill($item);
		$form->setDescription(tg('If you lost your password, fill in your login and you will receive an email with link, which will allow you to change your password.'));
		// handeling the form request
		$form->handleRequest();
		$this->assign($form->getIdentifier(), $form->toArray());
	}
	
	
	/**
	 * Change the Lost Password
	 */
	public function actionLostPasswordChange($args) {
		$item = new BaseLostPasswordChangeFormModel();
		$item->setToken($args->token);
		$form = new Form();
		$form->fill($item);
		$form->setDescription(tg('Here you can change your pasword.'));
		// handeling the form request
		$form->handleRequest();
		$this->assign($form->getIdentifier(), $form->toArray());
	}
	
	public function actionLostPasswordChangeDone($args) {
		$this->assign('title', tg('Your password has been changed'));
		$text = t('Now you can') 
		. ' <a href="' 
		. Request::getLinkSimple($this->package, 'Users', 'actionLogin') 
		. '" title="' . tg('login') . '">' . tg('login') . '</a> ' 
		. tg('with your new password.');
		$this->assign('text', $text);
	}
}

?>
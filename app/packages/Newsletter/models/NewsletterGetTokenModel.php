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


class NewsletterGetTokenModel extends AbstractVirtualModel {
	
	var $package = 'Newsletter';
	var $contact = null;
	var $id = 0;
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AtributesFactory::stdAccountEmail()
    		->setDescription('your e-mail to send the token to')
    		->addRestrictions(Restriction::R_NOT_EMPTY));
    	
    }

	public function Save($forceSaving=false) {
		
		parent::Save($forceSaving);
		
		$mail = new Email(); // defaults to using php "mail()"

		$mail->AddAddress($this->contact->email);
		
		$messageText = tg('This is e-mail from web #$webname#.<br /><br />Your personal code is: #$personalcode#<br /><br />If you would like to unsubscribe from newsletter, visit #$unsubscribeurl#. Your personal code is: #$personalcode#', 
			array(
				'webname' => Config::Get('PROJECT_TITLE'),
				'unsubscribeurl' => Request::getLinkSimple($this->package, 'Contacts', 'actionRegister', 
					array(
						'_pred_' => array(
							'token' => $this->contact->token,
							'unsubscribe' => 1,
							'email' => $this->contact->email,
							))),
				'personalcode' => $this->contact->token,
				));
		
		$mailAltBody = strip_tags(str_replace('<br />', "\n", $messageText));
		
		// fill basic fields
		$mail->Subject = tg('Newsletter get token');
		$mail->AltBody = $mailAltBody; 
		$mail->MsgHTML($messageText);

		// sending the email
		$sent = $mail->Send();
	}
	
	
	protected function checkFieldValue(&$meta, &$newData, $formStep) {
		if ($meta->getName() == 'email') {
			$tmp = NewsletterContactsModel::Search('NewsletterContactsModel', array('email = ?'), array($newData['email']));
			if (!$tmp) {
				$this->addMessage('errors', $meta->getName(), tg('Your e-mail is not subscribed.'));
			} else {
				$this->contact = $tmp[0];
			}
		}
		parent::checkFieldValue($meta, $newData, $formStep);
	}

} 

?>
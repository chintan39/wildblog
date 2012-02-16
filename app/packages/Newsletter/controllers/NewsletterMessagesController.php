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


class NewsletterMessagesController extends AbstractPagesController {
	
	/**
	 *
	 */
	public function actionCheckSending($args) {
		$message = $args;
		$message->addNonDbProperty('recipients');
		$message->recipients = $message->Find('NewsletterContactsModel');
		$this->assign('message', $message);

		$this->assign('title', tg('Check message before sending'));
	}
	

	/**
	 *
	 */
	public function actionSend($args) {
		$message = $args;
		$message->addNonDbProperty('recipients');
		$limit = 'limit 0, ' . Config::Get('NEWSLETTER_SEND_LIMIT');
		$tmp = NewsletterMessagesContactsModel::Search('NewsletterMessagesContactsModel', array('message = ? AND email_log = 0'), array($message->id), array($limit));
		$recIds = array();
		$message->recipients = array();
		if ($tmp) {
			foreach ($tmp as $i) {
				$recIds[] = $i->contact;
			}
			if (count($recIds)) {
				$filters = array(" id in (?" . str_repeat(", ?", count($recIds)-1) . ")");
				$values = $recIds;
			} else {
				$filters = array("id = ?");
				$values = array($recIds[0]);
			}
			$message->recipients = NewsletterContactsModel::Search('NewsletterContactsModel', $filters, $values);
		} 
		
		$messageContacts = new NewsletterMessagesContactsModel();
		$messageContactsTable = '`' . $messageContacts->getTableName() . '`';
		$messageID = $message->id;
		
		$result = 1;
		$errorEmails = $successEmails = array();
		
		if ($message->recipients) {
			foreach ($message->recipients as $recipient) {
				$mail = new Email(); // defaults to using php "mail()"
		
				$mail->AddReplyTo($message->getReplyto());
				$mail->SetFrom($message->getFrom());
				$mail->AddAddress($recipient->email);
				
				$messageText = $message->text . "\n--\n\n" . tg('This e-mail has been sent from web #$webname#.<br />If you would like to unsubscribe from newsletter, visit <a href="#$unsubscribeurl#">#$unsubscribeurl#</a>. Your personal code is: #$personalcode#', 
					array(
						'webname' => Config::Get('PROJECT_TITLE'),
						'unsubscribeurl' => Request::getLinkSimple($this->package, 'Contacts', 'actionRegister', array(
								'_pred_' => array(
									'token' => $recipient->token,
									'unsubscribe' => 1,
									'email' => $recipient->email,
									))),
						'personalcode' => $recipient->token,
						));
				
				$mailAltBody = strip_tags(str_replace('<br />', "\n", $messageText));
				
				// fill basic fields
				$mail->Subject = $message->title;
				$mail->AltBody = $mailAltBody; 
				$mail->MsgHTML($messageText);
		
				// sending the email
				$sent = $mail->Send();
				
				$logID = (int)$mail->logId;
				$currentTime = Utilities::now();
				$contactID = $recipient->id;
				$query = "
					UPDATE $messageContactsTable 
					SET email_log = $logID, 
					sent_time = '$currentTime' 
					WHERE
					message = $messageID AND contact = $contactID
					";
					
				if (Config::Get('DEBUG_MODE')) {
					Benchmark::log('sending newsletter e-mail SQL: ' . $query); // QUERY logger
				}
				
				$result1 = dbConnection::getInstance()->query($query);
				if (!$result1) {
					$errorEmails[] = $recipient->email;
				} else {
					$successEmails[] = $recipient->email;
				}
				$result &= $sent;
			}
		}
		
		if ($result) {
			$rm = tg('Newsletter has been sent successfully.');
		} else {
			$rm = tg('There are errors while sending a newsletter.');
		}

		$messagesToSend = NewsletterMessagesContactsModel::SearchCount('NewsletterMessagesContactsModel', array('message = ? AND email_log = 0'), array($message->id));
		
		$this->assign('messagesToSend', $messagesToSend);
		$this->assign('resendAction', Request::getSameLink());
		$this->assign('resultMessage', $rm);
		$this->assign('errorEmails', $errorEmails);
		$this->assign('successEmails', $successEmails);
		
		$this->assign('title', tg('Result of sending a newsletter'));
	}
	

	protected function getEditButtons() {
		return array(Form::FORM_BUTTON_SEND, Form::FORM_BUTTON_SAVE, Form::FORM_BUTTON_CANCEL);
	}

	protected function getEditActionsAfterHandlin() {
		return array(Form::FORM_BUTTON_SEND => array(
			'package' => $this->package, 
			'controller' => $this->name, 
			'action' => 'actionCheckSending'));
	}
}

?>
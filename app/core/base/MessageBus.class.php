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
 * Class handles message bus.
 * Messages are stored in DB.
 * Buffer is used.
 */

class MessageBus {
	
	const MESSAGE_TYPE_INFO = 1;
	const MESSAGE_TYPE_WARN = 2;
	const MESSAGE_TYPE_ERROR = 3;

	const MESSAGE_STORE_LOG = 1;
	const MESSAGE_STORE_NONE = 2;
	
	static public $buffer = array();
	static public $bufferLoaded = array();
	

	/**
	 * Send $message to $recipient.
	 */
	static public function sendMessage($text, $type=false, $segment=false, $recipient=false, $storing=false, $sender=false) {
		// default values
		if ($storing === false)
			$storing = self::MESSAGE_STORE_NONE;
		
		if ($segment === false)
			$segment = 'system';
		
		if ($type === false)
			$type = self::MESSAGE_TYPE_INFO;
		
		if ($sender === false)
			$sender = 'user::' . Permission::getActualUserId();
		
		if ($recipient === false)
			$recipient = 'user::' . Permission::getActualUserId();
		
		if (!array_key_exists($recipient, self::$buffer)) {
			self::$buffer[$recipient] = array();
		}
		
		// create new model
		$message = new BaseMessagesModel();
		$message->text = $text;
		$message->recipient = $recipient;
		$message->segment = $segment;
		$message->sender = $sender;
		$message->storing = $storing;
		$message->type = $type;
		$message->whenread = '0000-00-00 00:00:00';
		
		self::$buffer[$recipient][] = $message;
	}
	

	/**
	 * Gets $messages sent to $recipient.
	 * @return array of messages (object)
	 */
	static public function getMessages($segment=false, $recipient=false, $sender=false, $pop=false) {
		
		// TODO: what if user is not logged? message cannot be logged.. ... save Utilities::generatePassword() to session
		// TODO: add segment - to separate form messages from system-wide ones
		if ($recipient === false)
			$recipient = 'user::' . Permission::getActualUserId();
		
		self::loadBuffer($recipient);

		$tmpBuffer = array();
		foreach (self::$buffer[$recipient] as $key => $message) {
			// loop through messages and select which we need
			if (($sender && $message->sender == $sender || !$sender)
				&& ($segment && $message->segment == $segment || !$segment)) {
				$tmpBuffer[] = $message;
				if ($pop) {
					unset(self::$buffer[$recipient][$key]);
				}
			}
		}
		self::markRead($tmpBuffer, $pop);
		return $tmpBuffer;
	}


	/**
	 * Gets $messages sent to $recipient.
	 * @return array of types, array of messages in each type (object)
	 */
	static public function getMessagesByType($segment=false, $recipient=false, $sender=false, $pop=false) {
		$messages = self::getMessages($segment, $recipient, $sender, $pop);
		$result = array('info' => array(), 'warning' => array(), 'error' => array());
		foreach ($messages as $message) {
			switch ($message->type) {
			case self::MESSAGE_TYPE_INFO: $result['info'][] = $message; break; 
			case self::MESSAGE_TYPE_WARN: $result['warning'][] = $message; break; 
			case self::MESSAGE_TYPE_ERROR: $result['error'][] = $message; break; 
			}
			
		}
		return $result;
	}
	

	/**
	 * Gets $messages by type sent to $recipient and mark them read.
	 * @return array of types, type of messages (object)
	 */
	static public function popMessagesByType($segment=false, $recipient=false, $sender=false) {
		return self::getMessagesByType($segment, $recipient, $sender, true);
	}
	
	
	/**
	 * Gets $messages sent to $recipient and mark them read.
	 * @return array of messages (object)
	 */
	static public function popMessages($segment=false, $recipient=false, $sender=false) {
		return self::getMessages($segment, $recipient, $sender, true);
	}
	
	
	/**
	 * Mark messages as read.
	 * If storing is set to log, messages will be stored in db.
	 * @param $messages array of messages
	 */
	static public function markRead(&$messages, $force=false) {
		foreach ($messages as $message) {
			$message->whenread = Utilities::now();
			if ($force || $message->storing == self::MESSAGE_STORE_LOG) {
				$message->Save();
			}
		}
	}
	
	
	/**
	 * Store buffer to DB.
	 */
	static public function storeBuffer() {
		foreach (self::$buffer as $recipient => $messages) {
			foreach ($messages as $message) {
				$message->Save();
			}
		}
	}
	
	
	/**
	 * Load buffer from DB.
	 */
	static public function loadBuffer($recipient) {
		if (!array_key_exists($recipient, self::$bufferLoaded)) {
			if (!array_key_exists($recipient, self::$buffer)) {
				self::$buffer[$recipient] = array();
			}
			if ($messages = BaseMessagesModel::Search('BaseMessagesModel', array('recipient = ?', 'whenread = ?'), array($recipient, '0000-00-00 00:00:00'))) {
				self::$buffer[$recipient] = array_merge(self::$buffer[$recipient], $messages);
			}
			self::$bufferLoaded[$recipient] = true;
			// TODO: erase not logged items --- what?
		}
	}
	
	
	/**
	 * Clear buffer, messages will be forgotten.
	 */
	static public function clearBuffer() {
		unset(self::$buffer);
		self::$buffer = array();
	}
	
	
	static public function exportMessages($tpl_output, &$smarty) {
		if (strstr($tpl_output, '<!-- message_bus_adding -->') !== false) {
			Environment::$smarty->assign('messages', MessageBus::popMessagesByType());
			$messagesHtml = Environment::$smarty->fetch("file:/" . Themes::getTemplatePath('Base', Themes::getActualTheme(), 'part.displayMessages'));
			return str_replace('<!-- message_bus_adding -->', "<!-- message_bus_adding_begin -->\n" . $messagesHtml . "\n<!-- message_bus_adding_end -->\n" , $tpl_output);
		}
		return $tpl_output;
	}
	
}

function MessageBus__exportMessages($tpl_output, &$smarty) {
	return MessageBus::exportMessages($tpl_output, $smarty);
}

?>

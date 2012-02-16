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
 * There are defined some none-class functions, that can be used in any place.
 */

class Utilities {
	/**
	 * This returns the actual date and time in the DB format (YYYY-mm-dd HH:ii:ss).
	 * @return string Present data and time in the DB format (YYYY-mm-dd HH:ii:ss).
	 */
	static public function now() {
		return date("Y-m-d H:i:s");
	}
	
	
	/**
	 * This returns month's long name.
	 * @return number $number month number
	 */
	static public function monthNameLong($number) {
		$months = array(
		  'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
		  'September', 'October', 'November', 'December'
		);
		return tg($months[$number-1]);
	}
	
	
	/**
	 * This returns month's short name.
	 * @return number $number month number
	 */
	static public function monthNameShort($number) {
		$months = array(
		  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
		  'Dec' 
		);
		return tg($months[$number-1]);
	}
	
	
	/**
	 * This returns how old is the timestamp (translated using tg).
	 * @return number $timestamp time in any format
	 */
	static public function dateRelative($timestamp) {
		$now = date_create();
		$timestamp = (((int)$timestamp) > 10000) ? date_create('@'.$timestamp) : date_create($timestamp);
		$prefix = (($timestamp<$now) ? tg('before') : tg('in')) . ' ';
		$interval = date_diff($timestamp, $now);
		if ($interval->y)
			return $prefix.$interval->format('%y '.tg('years'));
		if ($interval->m)
			return $prefix.$interval->format('%m '.tg('months'));
		if ($interval->d)
			return $prefix.$interval->format('%d '.tg('days'));
		if ($interval->h)
			return $prefix.$interval->format('%h '.tg('hours'));
		if ($interval->i)
			return $prefix.$interval->format('%i '.tg('minutes'));
		if ($interval->s)
			return $prefix.$interval->format('%s '.tg('seconds'));
		return tg('now');
	}
	
	
	/**
	 * Convert digit place of the number to the nice form and retuns units as the second parameter.
	 * For example input 10029 (with precision 1) will be changed to 10.1 and unit will be kB.
	 * @param float &$value Number to convert (will be changed).
	 * @param string &$unit Unit of the number (will be changed).
	 */
	static public function niceDigitPlace(&$value, &$unit, $precision=0) {
		$prefixes = "kMGTP";
		$prefixIndex = -1;
		while ($value > 1024.0) {
			$value = (float)$value/1024.0;
			$prefixIndex++;
		}
		$value = round($value, $precision);
		$unit = (($prefixIndex >= 0) ? $prefixes[$prefixIndex] : "") . "B";
	}
	
	
	/**
	 * Removes diakritics from the string.
	 * @param <string> String with diacritics
	 * @return <string> String without diacritics
	 */
	static public function removeDiacritic($str) {
		$search  = array(
			'á', 'ä', 'č', 'ď', 'é', 'ě', 'í', 'ĺ', 'ľ', 'ň', 'ô', 'ó', 'ő', 'š', 'ř', 'ú', 'ů', 'ť', 'ý', 'ž',
			'Á', 'Ä', 'Č', 'Ď', 'É', 'Ě', 'Í', 'Ĺ', 'Ľ', 'Ň', 'Ô', 'Ó', 'Ö', 'Š', 'Ř', 'Ú', 'Ů', 'Ť', 'Ý', 'Ž');
		$replace = array(
			'a', 'a', 'c', 'D', 'e', 'e', 'i', 'l', 'l', 'n', 'o', 'o', 'o', 's', 'r', 'u', 'u', 't', 'y', 'z',
			'A', 'A', 'C', 'D', 'E', 'E', 'I', 'L', 'L', 'N', 'O', 'O', 'O', 'S', 'R', 'U', 'U', 'T', 'Y', 'Z');
		return str_replace($search, $replace, $str);
	}
	
	
	/**
	 * Makes an url-friendly string from the $str. String can include characters a-b,
	 * numbers 0-9 and the '-' character.
	 */
	static public function makeUrlPartFormat($str) {
		$str = strtolower(self::removeDiacritic($str));
		$str = preg_replace('/[^a-z0-9]/', ' ', $str);
		return strtolower(preg_replace('/ +/', '-', trim($str)));
	}
	
	
	/**
	 * Makes an url-friendly filename from the $filename. String can include characters a-b,
	 * numbers 0-9, the '-' character and one '.' before extention.
	 */
	static public function makeFileNameFormat($filename) {
		// preserve extention
		if (preg_match('/^(.*)(\.\w+)$/', $filename, $match)) {
			return self::makeUrlPartFormat($match[1]) . strtolower($match[2]);
		} else {
			return self::makeUrlPartFormat($filename);
		}
	}
	
	
	/**
	 * Adjust file name to be unique in the directory using adding -N suffix, 
	 * where N is smallest possible number.
	 */
	static public function getUniqueFileName($filename, $dir) {
		$suffixNum = 0;
		$suffix = '';
		
		// explode filename into base and extention
		if (preg_match('/^(.*)(\.\w+)$/', $filename, $match)) {
			$newFilenameBase = $match[1];
			$newFilenameExt = $match[2];
		} else {
			$newFilenameBase = $match[1];
			$newFilenameExt = '';
		}
		
		// loop through suffixes until there is no such file
		while (file_exists($dir . $newFilenameBase . $suffix . $newFilenameExt)) {
			$suffix = '-' . $suffixNum++;
		}
		
		return $newFilenameBase . $suffix . $newFilenameExt;
	}
	
	
	/**
	 * Checks if $str is in url-friendly format (can include characters a-b,
	 * numbers 0-9 and the '-' character).
	 */
	static public function checkUrlPartFormat($str) {
		return preg_match(REGEXP_URL_PART, $str);
	}
	
	
	/**
	 * Checks if $str is in text format
	 */
	static public function checkTextFormat($str) {
		return preg_match(REGEXP_TEXT_FORMAT, $str);
	}
	

	/**
	 * Checks if $str is in number format
	 */
	static public function checkNumberFormat($str) {
		return preg_match(REGEXP_NUMBER, $str);
	}
	

	/**
	 * Checks if $str is in E-mail format
	 */
	static public function checkEmailFormat($str) {
		return preg_match(REGEXP_EMAIL, $str);
	}
	

	/**
	 * Checks if $str is in link format
	 */
	static public function checkLinkFormat($str) {
		return preg_match(REGEXP_LINK, $str);
	}
	

	/**
	 * Checks if $str is in Date format
	 */
	static public function checkDateFormat($str) {
		list($y, $m, $d) = explode("-", $str);
		return preg_match(REGEXP_DATE, $str) && checkdate($m, $d, $y);
	}
	

	/**
	 * Checks if $str is in Time format
	 */
	static public function checkTimeFormat($str) {
		list($h, $m, $s) = explode(":", $str);
		return preg_match(REGEXP_TIME, $str) && ($h <= 23) && ($m <= 59) && ($s <= 59) && ($h >= 0) && ($m >= 0) && ($s >= 0);
	}
	

	/**
	 * Checks if $str is in color hexa format
	 */
	static public function checkColorRGBHexaFormat($str) {
		return preg_match(REGEXP_COLOR_RGB_HEXA, $str);
	}

	
	/**
	 * Checks if $str is in DateTime format
	 */
	static public function checkDateTimeFormat($str) {
		$expl = explode(" ", $str);
		if (count($expl) != 2) {
			return false;
		}
		return self::checkTimeFormat($expl[1]) && self::checkDateFormat($expl[0]);
	}
	

	/**
	 * Sort array by one of the attributes.
	 * This method suppose an array of arrays A. Suppose A has attribute $attr.
	 * @param <array> &$array array to be sorted (change an array in this parameter) 
	 * @param <string> $attr name of the property to sort by
	 * @param <int> $order sort order 1 .. classic order (by default), -1 .. opposite order
	 */
	static public function sortArrayByAttribute(&$array, $attr, $order=1) {
		function tmpCmp($a, $b) {
			global $attr;
			print_r($a);echo $attr;
			if ($a[$attr] == $b[$attr]) {
				return 0;
			}
			$order = ($order == -1) ? -1 : 1;
			return ($a[$attr] < $b[$attr]) ? -1*$order : 1*$order;
		}
		usort($array, 'tmpCmp');
	}
	
	
	/**
	 * Smarty truncate modifier plugin
	 *
	 * Type:     modifier<br>
	 * Name:     truncate<br>
	 * Purpose:  Truncate a string to a certain length if necessary,
	 *           optionally splitting in the middle of a word, and
	 *           appending the $etc string or inserting $etc into the middle.
	 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
	 *          truncate (Smarty online manual)
	 * @author   Monte Ohrt <monte at ohrt dot com>
	 * @param string
	 * @param integer
	 * @param string
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	static public function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
	{
		if ($length == 0)
			return '';
	
		if (strlen($string) > $length) {
			$length -= min($length, strlen($etc));
			if (!$break_words && !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
			}
			if(!$middle) {
				return substr($string, 0, $length) . $etc;
			} else {
				return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
			}
		} else {
			return $string;
		}
	}
	

	/**
	 * Hash function to hash the password.
	 */
	static public function hashPassword($text) {
		return md5($text);
	}
	
	
	/**
	 * Returns the element (specified by $key) from the array $array, shortening the array by that element.
	 * If no element is found, $default value is used to return.
	 */
	static public function assocShift(&$array, $key, $defaultValue=false) {
		if (isset($array[$key]) && $array[$key]) {
			$result = $array[$key];
			unset($array[$key]);
		} else {
			$result = $defaultValue;
		}
		return $result;
	}
	
	
	/**
	 * Generates pseudo-random password. This is not cryptographic very save, 
	 * but for our purpose good enough.
	 */
	static public function generatePassword() {
		return substr(md5(microtime()), 10, 16);  
	} 
	
	
	/**
	 * Translates static text. This can be called by controller for example.
	 * @param string $content Text to translate
	 * @param array $params parameters, that can be replaced in the translated text (for example number).
	 * @return string Translated text with replaced parameters.
	 */
	static public function translate($content, $params=array(), $kind, $forceTheme=false) {
		return Environment::getPackage('Base')->getController('Dictionary')->translate($content, $params, $kind, $forceTheme);
	}
	
	
	/**
	 * Smarty block function to translate block of text.
	 * @param array $params paramters of the block tag
	 * @param string $content text to translate (content of the tag)
	 * @param object $smarty Smarty object
	 * @param bool $repeat True if first tag, false else.
	 * @return string Translated text if closing tag. Nothing when opening tag.
	 */
	static public function smarty_translate($params, $content, &$smarty, &$repeat, $kind) {
		return Environment::getPackage('Base')->getController('Dictionary')->smarty_translate($params, $content, $smarty, $repeat, $kind);
	}


	/**
	 * Scans directory $dir to find all files with extention $extention
	 * @param $dir string
	 * @param $extention string if false directory is found
	 * @param $recursive bool If true, files will be returned with path and will be search 
	 * recursively. If false, files without path will be returned.
	 */
	static public function getFilesWithExtention($dir, $extention='php', $recursive=false) {
		$files = array();
		if (!is_dir($dir)) throw new Exception ("$dir is not a directory.");
		foreach (scandir($dir) as $file) {
			if (($extention && !is_dir($dir.$file) && substr($file, strrpos($file, '.') + 1) == $extention) || ($extention === false && is_dir($dir.$file) && $file != '.' && $file != '..' && $file{0} != '.')) {
				$files[] = $recursive ? $dir . $file : $file;
			} else if (is_dir($dir . $file) && $recursive && $file != '.' && $file != '..' && $file[0] != '.') {
				$files = array_merge($files, self::getFilesWithExtention($dir . $file . '/', $extention, true));
			}
		}
		return $files;
	}
	
	
	/** A simple FAST parser to convert BBCode to HTML
	 * Trade-in more restrictive grammar for speed and simplicty
	 *
	 * Syntax Sample:
	 * --------------
	 * [img]http://elouai.com/images/star.gif[/img]
	 * [url="http://elouai.com"]eLouai[/url]
	 * [mail="webmaster@elouai.com"]Webmaster[/mail]
	 * [size="25"]HUGE[/size]
	 * [color="red"]RED[/color]
	 * [b]bold[/b]
	 * [i]italic[/i]
	 * [u]underline[/u]
	 * [list][*]item[*]item[*]item[/list]
	 * [code]value="123";[/code]
	 * [quote]John said yadda yadda yadda[/quote]
	 *
	 * Usage:
	 * ------
	 * <?php include 'bb2html.php'; ?>
	 * <?php $htmltext = bb2html($bbtext); ?>
	 *
	 * (please do not remove credit)
	 * author: Louai Munajim
	 * website: http://elouai.com
	 * date: 2004/Apr/18
	 */
	static public function parseBBCode($text) {
		$bbcode = array("<", ">",
			"[list]", "[*]", "[/list]", 
			"[img]", "[/img]", 
			"[b]", "[/b]", 
			"[u]", "[/u]", 
			"[i]", "[/i]",
			'[color="', "[/color]",
			"[size=\"", "[/size]",
			'[url="', "[/url]",
			"[mail=\"", "[/mail]",
			"[code]", "[/code]",
			"[quote]", "[/quote]",
			'"]');
		$htmlcode = array("&lt;", "&gt;",
			"<ul>", "<li>", "</ul>", 
			"<img src=\"", "\">", 
			"<b>", "</b>", 
			"<u>", "</u>", 
			"<i>", "</i>",
			"<span style=\"color:", "</span>",
			"<span style=\"font-size:", "</span>",
			'<a href="', "</a>",
			"<a href=\"mailto:", "</a>",
			"<code>", "</code>",
			"<table width=100% bgcolor=lightgray><tr><td bgcolor=white>", "</td></tr></table>",
			'">');
		$newtext = strip_tags(str_replace($bbcode, $htmlcode, $text), '<ul><li><img><b><u><i><span><a><code><table><tr><td>');
		$newtext = nl2br($newtext);//second pass
		return $newtext;
	}
	
	
	/**
	 * Checks if $string is in BBCode format.
	 */
	static public function checkBBCodeFormat($string) {
		preg_match_all('/\[/', $string, $b1);
		preg_match_all('/\]/', $string, $b2);
		if ($b1 != $b2 && count($b1[0]) != count($b2[0])) {
			return false;
		}
		if (strpos($string, '<') || strpos($string, '>')) {
			return false;
		}
		return true;
	}

	
	/**
	 * Checks if $input is in html format. It checks if all opened tags are closed. 
	 * Param $allowedTags is a list of tags separated by '|' that can be used 
	 * in $input. If tag name ends with '/' then no closing tag is expected. 
	 * We can use <br> and also <br /> variant.
	 * @todo: allow characters '<' and '>' in tag's param value
	 * @param <string> $input text in html to check
	 * @param <string> $allowedTags list of allowed tags separated with '|' (lowercase)
	 * @return <bool, string> returns true if $input is valid html text, or error message if not
	 */
	static public function checkHTMLFormat($input, $allowedTags="a|abbr|acronym|address|applet|b|big|blockquote|br/|caption|cite|code|col/|colgroup|dd|del|dfn|div|dl|dt|em|h1|h2|h3|h4|h5|h6|hr/|i|img/|li|link/|ol|p|param/|pre|q|samp|script|small|span|strong|style|sub|sup|table|tbody|td|tfoot|th|thead|title|tr|tt|ul") {
		$allowedTagsArray = array_flip(explode('|', $allowedTags));
		$inputLength = strlen($input);
		
		// stack to store opened tags
		$tagStack = array();
	
		// actual offset in input
		$pos = 0;
		
		while (($pos = strpos($input, '<', $pos)) !== false) {
			// if input ends with '<', die
			if ($inputLength <= $pos+3)
				return tg('string ended unexpectacly');
			
			// check if this is ending tag
			if ($endingTag = ($input[$pos+1] == '/'))
				$pos++;
	
			// check tag name
			if (substr($input, $pos+1, 3) == '!--') {
				if (($pos = strpos($input, '-->', $pos+3)) === false)
					return tg('comment tag not ended');
				else 
					continue;
			}
			
			if (!preg_match('/\w+/', substr($input, $pos+1, 20), $m))
				return tg('could not found tag name');
			
			$tagName = strtolower($m[0]);
			$tmpTag = null;
			
			// pop all tags from stack that can be alone and are not the same as the actual one
			while ($endingTag && !empty($tagStack) && $tagName != ($tmpTag = array_pop($tagStack)) 
				&& array_key_exists($tmpTag.'/', $allowedTagsArray));
			
			// check if popped tag is the actual one
			if ($endingTag && $tmpTag != $tagName)
				return tg('ending tag').' '.$tagName.' '.tg('closes non-opened tag').' '.$tmpTag;
			
			$pos += strlen($tagName);
			if (($tmpPos1 = strpos($input, '>', $pos)) !== false && ($tmpPos2 = strpos($input, '<', $pos)) !== false && $tmpPos2 < $tmpPos1)
				return tg('tag').' '.$tagName.' '.tg('not closed');
			
			$pos = strpos($input, '>', $pos);
			
			if ($pos === false)
				return tg('tag').' '.$tagName.' '.tg('does not end');
			
			// tag has valid name
			if (!array_key_exists($tagName, $allowedTagsArray) && !array_key_exists($tagName.'/', $allowedTagsArray))
				return tg('tag').' '.$tagName.' '.tg('is not valid tag');			
	
			// store it
			if (!$endingTag)
				$tagStack[] = $tagName;
		}
		// clear all left tags that can be alone (without closing partner
		foreach ($tagStack as $k => $tag) {
			if (array_key_exists($tagName.'/', $allowedTagsArray))
				unset($tagStack[$k]);
		}
		if (!empty($tagStack))
			return tg('tag') . ' ' . $tagStack[0] . ' ' . tg('does not end');
		return true;

	}

	
	/**
	 * Removes values from array, which do not have subkeys corresponding 
	 * with the $key and $value specification.
	 * @param array $array Array to filter
	 * @param string $key key to find in
	 * @param string $value value to equal
	 * @param string $operator operator used to compare (=, >, <, <>, <=, >=)
	 * @param string $mode Mode array or object ('array', 'object')
	 * @return array filtered $array
	 */
	static public function arrayValueFilter($array, $key, $value, $operator='=', $mode='array') {
		$newarray = array();

		foreach(array_keys($array) as $k) {

			// switching the mode
			switch ($mode) {
				case 'object': $temp = $array[$k]->$key; break;
				default:
				case 'array': $temp = $array[$k][$key]; break;
			}
			
			// switching the operator
			if (($operator == '>'  && ($temp > $value))
			 || ($operator == '>=' && ($temp >= $value))
			 || ($operator == '<'  && ($temp < $value))
			 || ($operator == '<=' && ($temp <= $value))
			 || ($operator == '<>' && ($temp != $value))
			 || ($operator == '==' && ($temp == $value)))
				$newarray[$k] = $array[$k];
		}
        return $newarray; 
	}
	
	
	/**
	 * Returns array sorted against $key, $value specification
	 * @param array $array Array to sort
	 * @param string $key key to sort by
	 * @param string $direction direction of the sorting (asc, desc)
	 * @return array sorted $array
	 */
	static public function arrayValueSort(&$array, $key, $direction='asc', $mode='array') {
		
		$GLOBALS['__utilities_temp_mode'] = $mode;
		$GLOBALS['__utilities_temp_key'] = $key;
		$GLOBALS['__utilities_temp_direction'] = $direction;
		
		// use user defined function
		usort($array, 'arrayKeySortCallBack');
		
		unset($GLOBALS['__utilities_temp_mode']);
		unset($GLOBALS['__utilities_temp_key']);
		unset($GLOBALS['__utilities_temp_direction']);
		
		return $array;
	}
	
	
	/**
	 * Generates new 16-character long token.
	 * @return string 16-chars long token (similiar to password).
	 */
	static public function generateToken() {
		return self::generatePassword();
	}
	
	
	/**
	 * Makes relative url from relative path.
	 * @param string relative path
	 * @return string relative url
	 */
	static public function path2url($path) {
		$prefix = ($path{0} == '/') ? '/' : '';
		return preg_replace('/^' . self::string2regexp($prefix.DIR_PROJECT_PATH_MEDIA) . '/', $prefix.DIR_PROJECT_URL_MEDIA, $path);
	}
	
		
	/**
	 * Makes relative path from relative url.
	 * @param string relative url
	 * @return string relative path
	 */
	static public function url2path($url) {
		$prefix = ($url{0} == '/') ? '/' : '';
		return preg_replace('/^' . self::string2regexp($prefix.DIR_PROJECT_URL_MEDIA) . '/', $prefix.DIR_PROJECT_PATH_MEDIA, $url);
	}
	
	
	/**
	 * Makes backslashed strings to use in regular expressions.
	 * @param string $str
	 * @return string Backslashed string
	 */
	static public function string2regexp($str) {
		return str_replace(array('/', '.'), array('\/', '\.'), $str);
	}
	
	
	/**
	 * Concats two or more elements into one path.
	 */
	static public function concatPath() {
		$args = func_get_args();
		$res = implode('/', $args);
		while (strpos($res, '//') !== FALSE) {
			$res = str_replace('//', '/', $res);
		}
		return $res;
	}
	
		
	/**
	 * Creates directory structure.
	 */
	static public function createPath($urlPath) {
		$urlPath = explode('/', $urlPath);
		$prefix = '';
		array_pop($urlPath);
		foreach ($urlPath as $dir) {
			if (empty($dir)) {
				continue;
			}
			$prefix .= $dir;
			if (!is_dir($prefix)) {
				if (!mkdir($prefix)) {
					throw new Exception("Directory '$prefix' cannot be created. Check your file permissions.");
				}
				chmod($prefix, 0755);
			}
			$prefix .= '/';
		}
	}
	
	
	/**
	 * Returns extention from file name.
	 */
	static public function getExtentionFromFile($imagePath) {
		if (!preg_match('/\.(\w+)$/', $imagePath, $match)) {
			return false;
		} 
		return strtolower($match[1]);
	}
	

	/**
	 * Returns image from file name.
	 */
	static public function getImageFromFile($imagePath) {
		switch (self::getExtentionFromFile($imagePath)) {
		case 'jpeg':
		case 'jpg':
			return imagecreatefromjpeg($imagePath);
			break;
		case 'gif':
			return imagecreatefromgif($imagePath);
			break;
		case 'png':
			return imagecreatefrompng($imagePath);
			break;
		default:
			return false;
			break;
		}
	}
	

	/**
	 * Returns if file is image (according extention, not content).
	 */
	static public function fileIsImage($filePath) {
		switch (self::getExtentionFromFile($filePath)) {
		case 'jpeg':
		case 'jpg':
		case 'gif':
		case 'png':
			return true;
			break;
		default:
			return false;
			break;
		}
	}


	/**
	 * Stores image into file.
	 */
	static public function putImageToFile($imagePath, $image) {
		switch (self::getExtentionFromFile($imagePath)) {
		case 'jpeg':
		case 'jpg':
			return imagejpeg($image, $imagePath);
			break;
		case 'gif':
			return imagegif($image, $imagePath);
			break;
		case 'png':
			return imagepng($image, $imagePath);
			break;
		default:
			return false;
			break;
		}
	}


	/**
	 * Resize image if needed.
	 */
	static public function resizeImageIfNeeded($imagePath, $maxWidth, $maxHeight) {
		if (!($originalImage = self::getImageFromFile($imagePath))) {
			return false;
		}
		
		$origW = imagesx($originalImage);
		$origH = imagesy($originalImage);
		$ratioW = (float)$maxWidth/(float)$origW;
		$ratioH = (float)$maxHeight/(float)$origH;
		
		if ($ratioW < $ratioH && $ratioW < 1.0) {
			$sizeW = $maxWidth;
			$sizeH = round($maxWidth * $origH / $origW);
		} elseif ($ratioW > $ratioH && $ratioH < 1.0) {
			$sizeH = $maxHeight;
			$sizeW = round($maxHeight * $origW / $origH);
		} elseif ($ratioW == $ratioH && $ratioH < 1.0) {
			$sizeH = $maxHeight;
			$sizeW = $maxWidth;
		} else {
			return false;
		}

		$newImage = imagecreatetruecolor($sizeW, $sizeH);
		imagealphablending($newImage, false);
		imagefill($newImage, 0, 0, imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 255, 255, 255, 127)));
		imagesavealpha($newImage, true);				
		imagealphablending($newImage, true);
		imagecopyresampled(
			$newImage, 
			$originalImage, 
			0, 0, 
			0, 0,
			$sizeW, $sizeH, 
			$origW, $origH
			);
		
		return self::putImageToFile($imagePath, $newImage);
	}
	
	
	/**
	 * Returns remote addr.
	 */
	static public function getRemoteIP() {
		return $_SERVER['REMOTE_ADDR'];
	}
	
	
	/**
	 * Returns range of numbers.
	 * If $items is positive, then result won't be longer than $items
	 * and numbers in the end will be more rare.
	 * If $items is negative, then result won't be longer than $items
	 * and numbers in the begining will be more rare.
	 * @param int $from 
	 * @param int $to 
	 * @param int $items maximum count of items in result
	 * @return array Returns array of numbers
	 */
	static public function range($from, $to, $items, $down=false) {
		$range = $to - $from;
		if ($range < $items) {
			return range($from, $to);
		}
		$res = array();
		$max = 10.0;
		foreach (range(1, $items) as $x) {
			$xx = $x * atan($max) / $items;
			$offset = round(tan($xx) * $range / $max);
			$res[] = $down ? ($to - $offset) : ($from + $offset);
		}
		sort($res);
		return array_unique($res);
	}
	
	/**
	 * Decrypt a bas-64 value with a key using symetric some algorithm.
	 */
	static public function simpleDecrypt($value, $key) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CFB);
		$decodedValue = base64_decode($value);
		$valueRaw = substr($decodedValue, $iv_size);
		$iv = substr($decodedValue, 0, $iv_size);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $valueRaw, MCRYPT_MODE_CFB, $iv);
	}
	
	/**
	 * Encrypt a value with a key using some symetric algorithm and convert 
	 * the result into base-64.
	 */
	static public function simpleEncrypt($value, $key) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CFB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_CFB, $iv));
	}
	
}

/**
 * Global-used friendly alias for the static method in Utilities class.
 */
function Utilities__smarty_translate_p($params, $content, &$smarty, &$repeat) {
	return Utilities::smarty_translate($params, $content, $smarty, $repeat, BaseDictionaryModel::KIND_PROJECT_SPECIFIC);
}


/**
 * Global-used friendly alias for the static method in Utilities class.
 */
function Utilities__smarty_translate_g($params, $content, &$smarty, &$repeat) {
	return Utilities::smarty_translate($params, $content, $smarty, $repeat, BaseDictionaryModel::KIND_GENERAL);
}


/**
 * Global-used friendly alias for the static method in Utilities class.
 */
function Utilities__smarty_translate_u($params, $content, &$smarty, &$repeat) {
	return Utilities::smarty_translate($params, $content, $smarty, $repeat, BaseDictionaryModel::KIND_URL_PARTS);
}


/**
 * Translate function alias.
 */
function tp($content, $params=array()) {
	return Utilities::translate($content, $params, BaseDictionaryModel::KIND_PROJECT_SPECIFIC);
}


/**
 * Translate function alias.
 */
function tg($content, $params=array()) {
	return Utilities::translate($content, $params, BaseDictionaryModel::KIND_GENERAL);
}


/**
 * Translate function alias.
 */
function tu($content, $params=array(), $forceTheme=false) {
	return Utilities::translate($content, $params, BaseDictionaryModel::KIND_URL_PARTS, $forceTheme);
}


// user defined comparing function
function arrayKeySortCallBack($a, $b) {
	$direction = $GLOBALS['__utilities_temp_direction'];
	$key = $GLOBALS['__utilities_temp_key'];
	
	// switching the mode
	switch ($GLOBALS['__utilities_temp_mode']) {
		case 'objectstring':
			return strcasecmp($a->$key, $b->$key);
		case 'object':
			return $a->$key - $b->$key;
		case 'arraystring':
			return strcasecmp($a[$key], $b[$key]);
		default:
		case 'array': 
			return $a[$key] - $b[$key];
	}
}

?>

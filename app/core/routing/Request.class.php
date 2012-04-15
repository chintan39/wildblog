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
 * Request class represents the HTTP request, that should be executed by the application.
 * This class uses the Route class to find the correct controller, that should parse 
 * the request. The communication between controller and request takes place in the other 
 * direction too, espatialy when a link to some object is generated.
 */

require_once('Router.class.php');
require_once('RouterRule.class.php');
require_once('RouterAction.class.php');

class Request {

	static public $url, $get, $post, $session, $cookie, $action, $homepageAction, $router, $startTime, $actionLink, $implicit=false; 

	static private $uniqueNumber = 0;
	
	static private $requestType = 'normal';	/* possible values: 'normal', 'ajax' */
	
	/**
	 * Constructor
	 */
	static public function init() {
		self::$homepageAction = self::explodeLink(Config::Get('HOMEPAGE_ACTION'));
		self::$startTime = microtime(true);
	}
	
	
	/**
	 * Converts string expression of link to array expression.
	 * @param string $link Link in string format, for example: Blog::Posts::actionDetail::30
	 * @return array the same link in array
	 */
	static private function explodeLink($link) {
		$tmp = explode('::', $link);
		if (!isset($tmp[1])) {
			return null;
		}
		return array(
			'package' => $tmp[0], 
			'controller' => $tmp[1], 
			'action' => $tmp[2], 
			'item' => (isset($tmp[3]) ? $tmp[3] : null), 
			);
	}

	
	/**
	 * Converts string expression of link itself.
	 * @param string $link Link in string format, for example: Blog::Posts::actionDetail::30
	 * @return string the same link (url)
	 */
	static public function getShortLink($link) {
		$tmp = self::explodeLink($link);
		if (!$tmp) {
			return $link;
		}
		if ($tmp['item']) {
			return self::getLinkItem($tmp['package'], $tmp['controller'], $tmp['action'], $tmp['item']);
		} else {
			return self::getLinkSimple($tmp['package'], $tmp['controller'], $tmp['action']);
		}
	}
	
	
	/**
	 * This method collects all Route rulles from all controllers and then 
	 * finds actual correct route, that will be applyed.
	 */
	static public function handleRequest() {
		
		self::init();
		
		self::$url = self::parseURL();
		self::$get = $_GET;
		self::$post = $_POST;
		self::$session = $_SESSION;
		self::$cookie = $_COOKIE;
		self::assignBasicVars();

		Benchmark::logSpeed('Begin of handling a request.');
		
		// get all routes from controllers
		self::$router = new Router();
		
		// check actual APP version with project version, if not the same, DB need to be checked
		if (Config::Get('PROJECT_STATUS') == PROJECT_READY && strcmp(trim(file_get_contents(VERSION_FILE)), APP_VERSION) != 0) {
			Config::Set('PROJECT_STATUS', PROJECT_DB_NEED_CHECK, 'Project status', Config::INT, false); 
			Environment::getPackage('Base')->getController('Cache')->actionClearCache(null);
		}

		if (Config::Get('PROJECT_STATUS') == PROJECT_DB_NEED_CHECK) {
			Themes::setTmp(Themes::BACK_END);
			Environment::getPackage('Base')->getController('Database')->actionDbCheckSimple();
			Environment::$smarty->display('file:/' . Themes::getTemplatePath('Base', 'DefaultAdmin', 'init'));
			exit();
		}

		if (Config::Get('PROJECT_STATUS') == PROJECT_NOT_INSTALLED) {
			Themes::setTmp(Themes::BACK_END);
			Environment::getPackage('Base')->getController('Database')->actionDbInstall();
			Environment::$smarty->display('file:/' . Themes::getTemplatePath('Base', 'DefaultAdmin', 'install'));
			exit();
		}

		// get actual language from the url and params
		if (Config::Get('PROJECT_STATUS') == PROJECT_READY) {
			Language::parseURL(self::$url, self::$get);
		}		

		Benchmark::logSpeed('Setting controllers\' routes.');
		// setting all routes to router
		foreach (Environment::getRoutes() as $routes) {
			if ($routes->abstract)
				continue;
			$routes->setRouter();
		}
		
		Benchmark::logSpeed('Loading url dict.');		
		// load url dictionary
		if (Config::Get('PROJECT_STATUS') == PROJECT_READY) {
			Environment::getPackage('Base')->getController('Dictionary')->loadUrlDict();
		}

		Benchmark::logSpeed('Getting request action.');
		// get request action
		self::$router->getRequestAction();
		
		// settings locale
		setlocale(LC_ALL, 'cs_CZ.utf8');
		//var_dump(Language::getCode());exit;

		Benchmark::logSpeed('Loading regular dict.');
		// load dictionary
		if (Config::Get('PROJECT_STATUS') == PROJECT_READY) {
			Environment::getPackage('Base')->getController('Dictionary')->loadDict();
		}
		
		// assign main values to the template
		if (Config::Get('PROJECT_STATUS') == PROJECT_READY) {
			Environment::$smarty->assign('isHomepage', self::isHomepage());
			Environment::$smarty->assign('title', tp('Project Title'));
			Environment::$smarty->assign('projectTitle', tp('Project Title'));
			Environment::$smarty->assign('projectDescription', tp('Project Description'));
			Environment::$smarty->assign('pageDescription', tp(''));
			Environment::$smarty->assign('projectKeywords', tp('list of project keywords'));
			Environment::$smarty->assign('appVersion', APP_VERSION);
			Environment::$smarty->assign('appGenerator', APP_GENERATOR_NAME . ' version ' . APP_VERSION);
			Environment::$smarty->assign('dirLibs', DIR_LIBS);
			Environment::$smarty->assign('thisLink', self::getSameLink());
			Environment::$smarty->assign('requestLink', Request::$url['request']);
			Environment::$smarty->assign('requestIsAjax', self::isAjax());
			
			// benchmark tracking
			if (isset(Request::$get['stopbenchmark']))
				Benchmark::stopTracking();
			if (isset(Request::$get['startbenchmark']))
				Benchmark::startTracking();
			Environment::$smarty->assign('benchmarkIsTracking', Benchmark::isTracking());
			Environment::$smarty->assign('benchmarkChangeTracking', Benchmark::isTracking() ? 'stopbenchmark' : 'startbenchmark');

			// date info
			$today = date('j. XXX Y');
			$today = str_replace('XXX', Utilities::monthNameLong((int)date('m')), $today);
			Environment::$smarty->assign('today', $today);
			Environment::$smarty->assign('now', time());
			Environment::$smarty->assign('visitorsCount', Config::GetCond('BASE_VISITORS_COUNT', 0));
			
			Environment::$smarty->assign('frontendLanguages', Language::getLanguages(Themes::FRONT_END));
			Environment::$smarty->assign('backendLanguages', Language::getLanguages(Themes::BACK_END));

			Javascript::addFile(self::$url['base'] . 'app/libs/prototype.js');
			Javascript::addFile(self::$url['base'] . 'app/libs/wwbase.js');
		}
		
		Benchmark::logSpeed('Handling the request by router.');
		// handle the request
		self::$router->handleRequest();
	}
	
	
	/**
	 * Assign variables to the Smarty template engine to be accessible 
	 * in the templates.
	 */
	static private function assignBasicVars() {
		Environment::$smarty->assign('thisUrl', self::$url['pathAll']);
		Environment::$smarty->assign('base', self::$url['base']);
	}
	
	
	/**
	 * Returns request variables as an array.
	 */
	static public function getRequestStructure() {
		return array(
			'url' => self::$url, 
			'get' => self::$get, 
			'post' => self::$post, 
			'session' => self::$session, 
			'cookie' => self::$cookie);
	}
	
	
	/**
	 * Method analyzes the request URL and parses it into tokens.
	 * @return array url
	 */
	static private function parseURL() {
		$url = array();
		$url['pathAll'] = $_SERVER['REQUEST_URI'];
		$url['protocol'] = (!empty($_SERVER['HTTPS']) ? 'https' : 'http');
		$url['baseWithoutProtocol'] = $_SERVER['HTTP_HOST'] . '/' . (Config::Get('PROJECT_URL') ? Config::Get('PROJECT_URL') . '/' : '');
		$url['base'] = $url['protocol'] . '://' . $url['baseWithoutProtocol'];
		$url['request'] = $url['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$qMarkPos = strpos($_SERVER['REQUEST_URI'], '?');
		$url['pathRaw'] = ($qMarkPos !== false) ? substr($_SERVER['REQUEST_URI'], 0, $qMarkPos) : $_SERVER['REQUEST_URI'];
		
		/* check if the request is an ajax call */
		if (isset($_POST[REQUEST_TYPE_PARAM_NAME]) || isset($_GET[REQUEST_TYPE_PARAM_NAME]))
			self::$requestType = 'ajax';
		
		if (Config::Get('PROJECT_URL')) {
			$url['pathRaw'] = preg_replace('/^\/' . Config::Get('PROJECT_URL') . '/', '', $url['pathRaw']);
		}
		$url['path'] = array();
		foreach (explode('/', $url['pathRaw']) as $p) {
			if ($p) {
				$url['path'][] = $p;
			}
		}
		return $url;
	}
	
	
	static public function getRequestLocationFromString($str) {
		if (preg_match('/(\w+)::(\w+)::(\w+)::(\d{1,9})/', $str, $matches)) {
			$requestLocation = new RequestLocation();
			$requestLocation->package = $matches[1];
			$requestLocation->controller = $matches[2];
			$requestLocation->method = $matches[3];
			//$modelName = $matches[1] . $matches[2] . "Model";
			//$requestLocation->item = new $modelName($matches[4]);
			$requestLocation->item = $matches[4];
			return $requestLocation;
		}
		if (preg_match('/(\w+)::(\w+)::(\w+)/', $str, $matches)) {
			$requestLocation = new RequestLocation();
			$requestLocation->package = $matches[1];
			$requestLocation->controller = $matches[2];
			$requestLocation->method = $matches[3];
			return $requestLocation;
		}
		ErrorLogger::log(ErrorLogger::ERR_WARNING, "Action being converted to Link does not have right format: '$str'.");
		return null;
	}
	
	
	static public function getLinkString($str) {
		$requestLocation = self::getRequestLocationFromString($str);
		return self::getLinkFromRequestLocation($requestLocation);
	}
	
	
	static public function getLinkFromRequestLocation($requestLocation) {
		if ($requestLocation && $requestLocation->item)
			return self::getLinkItem($requestLocation->package, $requestLocation->controller, $requestLocation->method, $requestLocation->item);
		if ($requestLocation)
			return self::getLinkSimple($requestLocation->package, $requestLocation->controller, $requestLocation->method);
		return '';
	}
	
	
	/**
	 * Generates the link to the item, specified by the item itself.
	 * @param string $package Package name or empty (not have to be defined, 
	 * if controller is an instance), must be defined if controller is string
	 * @param string $controller Controller name or instance
	 * @param string $method Controller method name
	 * @param object $dataItem Item to link to
	 * @param array $args additional arguments, for more info @see Router::makeLink
	 * @return string Returns link to the specific method as string
	 */
	static public function getLinkItem($package, $controller, $method, $dataItem, $args=null) {
		return self::$router->getLinkItem($package, $controller, $method, $dataItem, $args);
	}
	
	
	/**
	 * Generates the link to the item, specified by filters and values, that 
	 * specify the item.
	 * @param string $package Package name or empty (not have to be defined, 
	 * if controller is an instance), must be defined if controller is string
	 * @param string $controller Controller name or instance
	 * @param string $method Controller method name
	 * @param array $filters Filters for specification of the item
	 * @param array $values Values for specification of the item
	 * @return string Returns link to the specific method as string
	 */
	static public function getLinkFilter($package, $controller, $method, $filters, $values, $args=null) {
		return self::$router->getLinkFilter($package, $controller, $method, $filters, $values, $args);
	}
	
	
	/**
	 * Generates the link to the method of the specified controller.
	 * @param string $package Package name or empty (not have to be defined, 
	 * if controller is an instance), must be defined if controller is string
	 * @param string $controller Controller name or instance
	 * @param string $method Controller method name
	 * @return string Returns link to the specific method as string
	 */
	static public function getLinkSimple($package, $controller, $method, $args=null) {
		return self::$router->getLinkSimple($package, $controller, $method, $args);
	}
	
	
	/**
	 * Same as getLinkSimple, but package, controller and action is used from self::homepageAction
	 * @return string Returns link to the specific method as string
	 * @see getLinkSimple
	 */
	static public function getLinkHomePage($args=array()) {
		return self::getLinkSimple(self::$homepageAction['package'], self::$homepageAction['controller'], self::$homepageAction['action'], $args); 
	}
	
	
	/**
	 * Generates the link to the same page, but some parameters (paging) could be change.
	 * @param array $args New arguments, old will be overwritten
	 * @return string Returns link to the same method as string
	 */
	static public function getSameLink($args=array()) {
		return self::$router->getSameLink($args);
	}
	

	/**
	 * Redirects the browser using 302 HTTP code (Found, better See Other, but some user agents do not understand it). 
	 * Used to redirection after update etc.
	 * @param string $link Link to redirect to.
	 */
	static public function redirect($link) {
		self::sendHTTPHeader(302);
		self::redirectLocation($link);
	}


	/**
	 * Redirects the browser using 301 HTTP code (Moved Permanently). Used to pernament 
	 * redirection after changing uri of the page.
	 * @param string $link Link to redirect to.
	 */
	static public function redirectPerm($link) {
		self::sendHTTPHeader(301);
		self::redirectLocation($link);
	}
	
	
	static private function redirectLocation($link) {
		header('Location: '.$link);
		self::finish();
	}
	
		
	static public function finish() {
		MessageBus::storeBuffer();
		exit();
	}


	static private function sendHTTPHeader($code=302) {
		switch ($code) {
		case 302: header('HTTP/1.1 302 Found'); break;
		case 301: header('HTTP/1.1 301 Moved Permanently'); break;
		}
	}

	
	/**
	 * Sets and saves the action which is aplied for the current request.
	 * @param mixed $action Assoc array with keys: package(string), controller(string), method(string), item(object)..
	 */
	static public function storeRequestAction(&$action) {
		self::$action = $action;
		if ($action['item']) {
			self::$actionLink = self::getLinkItem($action['package'], $action['controller'], $action['method'], $action['item']);
		} else {
			self::$actionLink = self::getLinkSimple($action['package'], $action['controller'], $action['method']);
		}
	}
	
	
	/**
	 * Returns the saved action, which is aplied for the current request.
	 * @return mixed $action Assoc array with keys: package(string), controller(object), method(string), item(object)..
	 */
	static public function getRequestAction() {
		return self::$action;
	}
	
	
	/**
	 * Returns link of the saved action, which is aplied for the current request.
	 * @return string $actionLink Actual pure link (with no other data - sorting, etc).
	 */
	static public function getRequestActionLink() {
		return self::$actionLink;
	}
	
	/**
	 * Checks if the homepage action is the same as the action defined by the parameters.
	 * Actions are equal if package, controller and action are equal.
	 * @param string $package 
	 * @param string $controller 
	 * @param string $action
	 * @return bool True if actions are equal.
	 */
	static public function checkHomepageAction($package, $controller, $action) {
		return (self::$homepageAction['action'] == $action
			&& self::$homepageAction['controller'] == $controller
			&& self::$homepageAction['package'] == $package);
	}
	
	
	/**
	 * Checks if current action is homepage action.
	 * @return bool True if current action is homepage action
	 */
	static public function isHomepage() {
		return self::checkHomepageAction(self::$action['package'], self::$action['controller'], self::$action['method'], self::$action['item']);
	}

	
	/**
	 * Check permission of the current user to the action specified by
	 * $package, $controller and $method paramaters.
	 * @param string $package
	 * @param string $controller
	 * @param string $method
	 * @return bool true if the current user has permission to the action
	 */
	static public function checkPermission($package, $controller, $method) {
		return self::$router->checkPermission($package, $controller, $method);
	}
	
	
	/**
	 * Sets the mimetype of the HTTP respond, text/html by default.
	 */
	static public function setMimeType($mimeType) {
		self::$router->setMimeType($mimeType);
	}
	

	/**
	 * Sets the coding of the HTTP respond, utf-8 by default.
	 */
	static public function setCoding($coding) {
		self::$router->setCoding($coding);
	}
	

	/**
	 * Returns a unique number in request.
	 * @return int unique number in request
	 */
	static public function getUniqueNumber() {
		self::$uniqueNumber++;
		return self::$uniqueNumber;
	}
	
	static public function setTemplate($tpl) {
		self::$action['template'] = $tpl;
	}

	static public function isAjax() {
		return self::$requestType == 'ajax';
	}
	
}


?>
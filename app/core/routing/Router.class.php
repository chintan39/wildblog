<?php

/**
 * This class alows to define rules for mapping a HTTP request to the special action 
 * in controllers.
 * Using the other way, it alows to generate an URL from controller and constroller's 
 * action specification.
 */
class Router {
	
	var $routesActions=array(); 	// Actions are used to define the main content of the page
	var $routesSubactions=array(); 	// Subactions are used to get piece of content, but not the main content
	static public $instance = false;			// singleton instance
	public $mimeType = 'text/html';
	public $coding = 'UTF-8';
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		self::$instance = $this;
	}
	
	static private function getInstance() {
		if (self::$instance === false) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __getAction($routesObject, $methodName, $actionType) {
		// we have to decide if the order is action or subaction and to store it to the right place
		if ($actionType == RouterRule::RULE_ACTION) {
			$routes = &$this->routesActions;
		} else {
			$routes = &$this->routesSubactions;
		}

		// package is defined in routesObject
		$package = $routesObject->package;
		$routesName = $routesObject->getName();
		
		return $routes[$package][$routesName][$methodName];
	}
	
	
	/**
	 * Adds an rule to the set of route rules.
	 * This is the basic version, rules must be specified before and given as the argument.
	 * @param object $routesObject
	 * @param string $methodName
	 * @param int $actionType
	 */
	 private function addAction($routesObject, $methodName, $actionType) {
		// we have to decide if the order is action or subaction and to store it to the right place
		if ($actionType == RouterRule::RULE_ACTION) {
			$routes = &$this->routesActions;
		} else {
			$routes = &$this->routesSubactions;
		}
		
		// package is defined in routesObject
		$package = $routesObject->package;
		$routesName = $routesObject->getName();

		// we have to create an empty array, if it is not created yet
		if (!isset($routes[$package]) || !is_array($routes[$package])) {
			$routes[$package] = array();
		}
		if (!isset($routes[$package][$routesName]) || !is_array($routes[$package][$routesName])) {
			$routes[$package][$routesName] = array();
		}
		
		// save the rule
		$action = new RouterAction();
		$action->package = $package;
		$action->routesObject = $routesObject; 
		$action->order = $actionType;
		$action->action = $methodName;
		$routes[$package][$routesName][$methodName] = $action;
		return $action;
	}


	/**
	 * Adds an action rule to the set of route rules.
	 * This is more comfortable version, it is not possible define the rules manualy, 
	 * rules are parsed from the format of the url, get and post arguments.
	 * This version is more alike composing a link.
	 * @param object $routesObject
	 * @param string $methodName
	 * @see addSimpleRule()
	 */
	static public function registerAction($routesObject, $methodName) {
		self::setDefaultRuleValues($permission, $branch);
		return self::getInstance()->addAction($routesObject, $methodName, RouterRule::RULE_ACTION);
	}


	/**
	 * @param object $routesObject
	 * @param string $methodName
	 * @see addSimpleRule()
	 */
	static public function getAction($routesObject, $methodName, $actionType=RouterRule::RULE_ACTION) {
		return self::getInstance()->__getAction($routesObject, $methodName, $actionType);
	}


	/**
	 * Adds a subaction rule to the set of route rules.
	 * This is more comfortable version, it is not possible define the rules manualy, 
	 * rules are parsed from the format of the url, get and post arguments.
	 * This version is more alike composing a link.
	 * @param object $routesObject
	 * @param string $methodName
	 * @see addSimpleRule()
	 */
	static public function registerSubaction($routesObject, $methodName) {
		self::setDefaultRuleValues($permission, $branch);
		return self::getInstance()->addAction($routesObject, $methodName, RouterRule::RULE_SUBACTION);
	}
	
	
	/**
	 * Defualt rule values are Permission:all, Theme:frontpage
	 */
	static private function setDefaultRuleValues(&$permission, &$branch) {
		if ($permission === null) {
			$permission = Permission::$ALL;
		}
		if ($branch === null) {
			$branch = Themes::FRONT_END;
		}
	}


	/**
	 * Returns actual action if found.
	 * Then store the action for the future use.
	 */
	public function getRequestAction() {
		
		// we have to find the main action - controller and method which will be aplied for the current request		
		$actions = $this->getRequestActions($this->routesActions);
		
		// we cannot have more actions
		if (count($actions) > 1) {
			throw new Exception('Too many actions for the current request. It is allowed only one action per request.');
		}

		// set implicit action in no other is found
		if (count($actions) == 0) {
			$actions = array(
				array(
					'package' => 'Base', 
					'controller' => Environment::getPackage('Base')->getController('Implicit'), 
					'method' => 'actionImplicit', 
					'item' => null,
					'template' => 'notFound',
					'branch' => Themes::FRONT_END,
				)
			);
		}
		
		// store the action for the future use by other controllers (by making the LinkMenu for example).
		Request::storeRequestAction($actions[0]);

	}
	
	
	/**
	 * The HTTP request is analyze and all apropriate controller's actions are fired.
	 * Only one (should be the last one) action should generate the HTML using the 
	 * Smarty template engine.
	 * First the main action is found, but not fired, only stored. 
	 * Second the subactions are found and fired.
	 * After all subactions the main action is fired, if it is found. 
	 * If no main action is found, ImplicitController is used.
	 */
	public function handleRequest() {

		// get the main action
		$action = Request::getRequestAction();
		Environment::refreshSession();
		
		// handle subactions (we can sort them, but not necessary at pressent time)
		$subactions = $this->getRequestSubctions();
		foreach ($subactions as $subaction) {
			Benchmark::logSpeed('Fire subaction ' . $subaction['package'] . '::' . $subaction['controller']->getName() . '::' . $subaction['method'] . '.');
			$this->fireAction($subaction);
		}

		Benchmark::logSpeed('Fire the main action.');
		Benchmark::log('Begin of ' . get_class($action['controller']) . '->' . $action['method']);
		
		$this->fireAction($action);
		$this->display($action);
		Benchmark::log('End of ' . get_class($action['controller']) . '->' . $action['method']);
		Benchmark::log('Begin of hit count for ' . get_class($action['controller']) . '->' . $action['method']);
		$this->storeHit($action);
		MessageBus::storeBuffer();
		Benchmark::log('End of hit count for ' . get_class($action['controller']) . '->' . $action['method']);
		
	}

	
	private function storeHit($action) {
		$hit = new BaseHitsModel();
		$hit->url = Request::$url['pathAll'];
		$hit->action = $action['package'] . '::' . get_class($action['controller']) . '::' . $action['method'];
		$hit->ip = Utilities::getRemoteIP();
		$hit->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$hit->memory_consumption = memory_get_usage(true) / (1024.0 * 1024.0);
		$hit->generation_time = microtime(true) - Request::$startTime;
		if ($action['item']) {
			if (is_object($action['item']) && property_exists($action['item'], 'id')) {
				$hit->item = $action['item']->id;
			} else {
				$hit->item = var_export($action['item'], true);
			}
		} else {
			$hit->item = '';
		}
		$hit->lang = Language::getCode();
		$hit->Save();
	}
	
	/**
	 * Sets the mimetype of the HTTP respond, text/html by default.
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}
	
	
	/**
	 * Sets the coding of the HTTP respond, utf-8 by default.
	 */
	public function setCoding($coding) {
		$this->coding = $coding;
	}
	
	
	/**
	 * Display the Smarty template (HTML template at most cases).
	 */
	public function display($action) {
		$ex = explode('|', $action['template']);
		if (count($ex) == 2) {
			$package = $ex[0];
			$template = $ex[1];
		} else {
			$package = $action['package'];
			$template = $action['template'];
		}
		$theme = Themes::getThemeFromBranch($action['branch']);

		// we have to know, which theme we use
		Environment::$smarty->assign("thisTheme", $theme);
		Environment::$smarty->assign("generalTheme", $theme);
		Environment::$smarty->assign("frontendTheme", Config::Get("THEME_FRONT_END"));

		if ($template) {
			Environment::$smarty->mime_type = $this->mimeType;
			Environment::$smarty->coding = $this->coding;
			Environment::$smarty->display("file:/" . Themes::getTemplatePath($package, $theme, $template));
		}
	}
	
	
	/**
	 * Fires the action specified by the action definition in parameter. 
	 * @param mixed $action Assoc array with keys: controller(object), method(string), item(object)..
	 */
	private function fireAction(&$action) {
		$package = $action['package'];
		$controller = $action['controller'];
		$method = $action['method'];
		$item = $action['item'];
		Benchmark::log('Begin of ' . get_class($action['controller']) . '->' . $method);
		$controller->$method($item);
		Benchmark::log('End of ' . get_class($action['controller']) . '->' . $method);
	}
	
	
	/**
	 * Returns all actions from given action set, which should be aplied for the current request. 
	 * @param mixed $routesActions Actions set - actions or subactions.
	 * @return mixed Actions, which are aplied for the current request.
	 */
	private function getRequestActions(&$routesActions) {
		
		$actions = array();
		foreach ($routesActions as $package => $packageActions) {
			foreach ($packageActions as $controllerRoutes) {
				foreach ($controllerRoutes as $method => $route) {
					Themes::setTmp($route->branch);
					$filters = array();
					$values = array();
					$condition = 1;
					$this->setConditions($route->rules, $condition, $filters, $values);
					$items = false;
					if ($condition && (!$filters || $filters && ($items = $route->routesObject->checkRequestCondition($filters, $values)))) {
						if (is_array($items) && count($items)) {
							$item = $items[0];
						} else {
							$item = null;
						}
						if (Permission::check($route->permission)) {
							// save the action
							$actions[] = array(
								'package' => $package, 
								'controller' => Environment::getPackage($route->package)->getController($route->routesObject->name), 
								'method' => $method, 
								'item' => $item,
								'branch' => $route->branch,
								'template' => $route->template,
								);
						} else {
							if ($route->order == RouterRule::RULE_ACTION) {
								// TODO: redirect storing make more complex, define in some class as static method
								$_SESSION['login_redirect'] = $package . '::' . $route->routesObject->getName() . '::' . $method . (($item) ? ('::' . implode(',', $filters) . '::' . implode(',', $values)) : '');
								Request::redirect(Request::getLinkSimple('Base', Environment::getPackage('Base')->getController('Users'), 'actionLogin'));
							}
						}
					}
				}
			}
		}
		return $actions;
	}
	
	/**
	 * Returns all actions from given action set, which should be aplied for the current request. 
	 * @param mixed $routesActions Actions set - actions or subactions.
	 * @return mixed Actions, which are aplied for the current request.
	 */
	private function getRequestSubctions() {
		
		$action = Request::getRequestAction();
		$actions = array();
		$ex = explode('|', $action['template']);
		if (count($ex) == 1) {
			$template = $action['package'] . '.' . $action['template'];
		} else {
			$template = $ex[0] . '.' . $ex[1];
		}
		// get all templates depended on the actual template - in array keys
		$subtemplates = Themes::getSubTemplates(Themes::getActualTheme(), $template);
		
		// find all subactions with this depending templates
		foreach ($this->routesSubactions as $package => $packageActions) {
			foreach ($packageActions as $controllerRoutes) {
				foreach ($controllerRoutes as $method => $route) {
					if (array_key_exists($package . '.' . $route->template, $subtemplates) && Permission::check($route->permission)) {
						$actions[] = array(
							'package' => $package, 
							'controller' => Environment::getPackage($package)->getController($route->routesObject->getName()), 
							'method' => $method, 
							'item' => false,
							'branch' => $route->branch,
							'template' => $route->template,
							);
					}
				}
			}
		}
		return $actions;
	}
	 
	/**
	 * Check the conditions comparing the request. Then changes the parameters.
	 * Parameters are given by reference.
	 */
	private function setConditions(&$routeRules, &$condition, &$filters, &$values) {
		
		if (count($routeRules)) {
			// a hack to search first VALUE_ATTRIBUTE and VALUE_REGEXP and then VALUE_ATTRIBUTE rules
			// we need to cycle twice because of the hack
			for ($i=0; $i<2; ++$i) {
				foreach ($routeRules as $r) {
					// we skip RouterRule::VALUE_ATTRIBUTE first time and another the second time
					if (($i==0 && $r->valueType == RouterRule::VALUE_ATTRIBUTE) || ($i!=0 && $r->valueType != RouterRule::VALUE_ATTRIBUTE)) {
						continue;
					}
					switch ($r->ruleType) {
						case RouterRule::RULE_PATH: $src = isset(Request::$url["path"][$r->ruleSpec]) ? Request::$url["path"][$r->ruleSpec] : ""; break;
						case RouterRule::RULE_GET: $src = isset(Request::$get[$r->ruleSpec]) ? Request::$get[$r->ruleSpec] : false; break;
						default: throw new Exception('Unsupported Request rule type.');
					}
					switch ($r->valueType) {
						case RouterRule::VALUE_ATTRIBUTE:
								$filters[] = "$r->valueSpec = ?"; 
								$values[] = $src;
								break;
						case RouterRule::VALUE_STRING:
							// in case that value is $, it means, that source should be empty, 
							// (for example in path definition /posts/edit/$ means, that after "edit/" 
							// no other continuation can follow
							$condition &= ($r->valueSpec == '$') ? ($src === '') : ($src == tu($r->valueSpec, array(), Themes::getActualBranch()));
							break;
						case RouterRule::VALUE_REGEXP:
							$condition &= (preg_match("/" . $r->valueSpec . "/", $src, $matches) > 0);
							// we analyse the url's regular expression's brackets and we assign the names to the brackets
							// if the brackets are named, bracket's name will be added to filters and real value from URL will be added to values
							foreach ($matches as $bracketPosition => $bracketContent) {
								$identificatorPosition = ($bracketPosition-1);
								if (array_key_exists($identificatorPosition, $r->valueIdent) && $r->valueIdent[$identificatorPosition]) {
									$filters[] = $r->valueIdent[$identificatorPosition];
									$values[] = $bracketContent;
								}
							}
							break;
						default: throw new Exception('Unsupported Request value type.');
					}
					// we can return before
					if (!$condition) {
						return;
					}
				}
			}
		} else {
			throw new Exception('No rules by route.');
		}
	}
	
	
	/**
	 * Returns the route which is defined with the controller's action specified.
	 */
	public function getLinkItem($package, $controller, $method, $item, $args=null) {
		$route = $this->getRoute($package, $controller->getName(), $method);
		if (!$route) { 
			throw new Exception("Unsupported Request route \"$method\" in controller ".get_class($controller));
			return "";
		}

		// if we have only ID, we load the item from DB (we use controller from route)
		if (!is_object($item) && preg_match('/^\w+$/', $item)) {
			$item = $controller->getItem($item);
		}
		return $this->getRouteItemLink($route, $item, $args);
	}
	
	
	/**
	 * Same as getLinkItem, but the item is specified by SQL commands and values.
	 * @see getLinkItem
	 */
	public function getLinkFilter($package, $controller, $method, $filters, $values, $args=null) {
		$route = $this->getRoute($package, $controller->getName(), $method);
		if (!$route) { 
			throw new Exception("Unsupported Request route \"$method\" in controller ".get_class($controller));
			return '';
		}
		$item = $route->routesObject->getItemFilter($filters, $values);
		return $this->getRouteItemLink($route, $item, $args);
	}
	
	
	/**
	 * Same as getLinkItem, but the item is specified by SQL commands and values.
	 * @param string $package
	 * @param object $controller
	 * @param string $method
	 * @param array $args
	 * @see getLinkItem
	 */
	public function getLinkSimple($package, $controller, $method, $args=null) {
		$route = $this->getRoute($package, $controller->getName(), $method);
		if (!$route) {
			throw new Exception("Unsupported Request route \"$method\" in controller ".get_class($controller));
			return "";
		}
		return $this->getRouteLink($route, $args);
	}
	
	
	/**
	 * Returns the route definition from the controller's name and name of the action.
	 * @param string $package
	 * @param string $controllerClassName
	 * @param string $method
	 */
	private function getRoute($package, $controllerName, $method) {
		if (!isset($this->routesActions[$package]) || !isset($this->routesActions[$package][$controllerName]) || !isset($this->routesActions[$package][$controllerName][$method])) {
			return null;
		}
		return $this->routesActions[$package][$controllerName][$method];
	}
	
	
	/**
	 * Returns the link to the specified Item using the route definition.
	 * @param array $route route rules specification 
	 * @param object $item object to link to
	 * @param array $args additional arguments, like language
	 */
	private function getRouteItemLink($route, $item, $args=null) {
		$reqStructure = Request::getRequestStructure();
		$newPath = array();
		$reqStructure["get"] = array();
		$pathChanged = false;

		foreach ($route->rules as $r) {
			switch ($r->valueType) {
				case RouterRule::VALUE_ATTRIBUTE: 
					$attr = $r->valueSpec;
					if ($item === false) {
						throw new Exception('Method getRouteItemLink can not accept routes with item specification or un-existed item used.');
					} else {
						if (!$item->$attr) {
							throw new Exception('Attribute ' . $attr . ' of model ' . get_class($item) . ' is empty! (maybe table problem?)');
						}
						$src = $item->$attr;
					}
					break;
				case RouterRule::VALUE_STRING: 
					$src = tu($r->valueSpec, array(), Themes::getActualBranch()); 
					break;
				case RouterRule::VALUE_REGEXP:
					$src = ""; // reconstruction of the url is not possible in default case
					$outputRegExp = $inputRegExp = $r->originalSpec;
					if (preg_match_all(REGEXP_URL_REGEXP_BRACKETS_EXTRACTION, $r->originalSpec, $brackets)) {
						// we go throught brackets' content
						foreach ($brackets[1] as $bracketsPosition => $bracketsContent) {
							// we search identificator in the bracket's content
							if (preg_match(REGEXP_URL_REGEXP_IDENTIFICATOR_EXTRACTION, $bracketsContent, $ident)) {
								// we need to replace the bracket with identificator using the original value
								$outputRegExp = str_replace($brackets[0][$bracketsPosition], $item[$r->valueIdent[$bracketsPosition]], $outputRegExp);
							}
						}
					}
					// errase characters ^ and $
					$outputRegExp = str_replace("^", "", $outputRegExp);
					$outputRegExp = str_replace('$', "", $outputRegExp);
					$src = $outputRegExp;
					break;
				default: throw new Exception('Unsupported Request value type.');
			}
			
			switch ($r->ruleType) {
				case RouterRule::RULE_PATH: 
					if ($src != '$') {
						$newPath[$r->ruleSpec] = $src;
					}
					$pathChanged = true;
					break;
				case RouterRule::RULE_GET: 
					$reqStructure["get"][$r->ruleSpec] = ($src == '$') ? false : $src; 
					break;
				default: throw new Exception('Unsupported Request rule type.');
			}
		}
		
		$reqStructure['url']['protocol'] = Config::Get('PROJECT_USE_HTTPS_' . $route->branch) ? 'https' : 'http';
		$reqStructure['url']['base'] = $reqStructure['url']['protocol'] . '://' . $reqStructure['url']['baseWithoutProtocol'];
		if ($pathChanged) {
			$reqStructure['url']['path'] = $newPath;
		}
		return $this->makeLink($reqStructure, $args);
	}
	
	
	/**
	 * Returns the link to the specified route, but Item is not specified.
	 * @see getRouteItemLink
	 */
	private function getRouteLink($route, $args=null) {
		return $this->getRouteItemLink($route, false, $args);
	}
	
	
	/**
	 * Generates the link from the modified request structure.
	 * @param array $reqStructure preddefined request structure
	 * @param array 
	 */
	private function makeLink($reqStructure, $args=array()) {
		
		// add new arguments without language arguments
		if (is_array($args)) {
			// frontend language
			if (isset($args['lang_frontend']) && $args['lang_frontend']) {
				$reqStructure['lang_frontend'] = $args['lang_frontend'];
				unset($args['lang_frontend']);
			}
			// backend language
			if (isset($args['lang_backend']) && $args['lang_backend']) {
				$reqStructure['lang_backend'] = $args['lang_backend'];
				unset($args['lang_backend']);
			}
			// __project__ specification
			if (!isset($args['__project__']) || !$args['__project__']) {
				unset($reqStructure['get']['__project__']);
			}
			// other arguments will be url arguments
			foreach ($args as $k => $v) {
				if ($v === PRESERVE_VALUE) {
					if (isset(Request::$get[$k])) {
						$reqStructure["get"][$k] = Request::$get[$k];
					}
				} elseif ($v !== null) {
					$reqStructure["get"][$k] = $v;
				}
			}
		} else {
			unset($reqStructure['get']['__project__']);
		}

		// set backend language if changed
		if (isset($reqStructure['lang_backend']) && $reqStructure['lang_backend']) {
			if ($reqStructure['lang_backend'] != Config::Get('DEFAULT_LANGUAGE_BACK_END')) {
				$reqStructure['get']['lang'] = $reqStructure['lang_backend'];
			} else {
				unset($reqStructure['get']['lang']);
			}
		}

		// set frontend language if changed
		$lang_frontend = (isset($reqStructure['lang_frontend']) && $reqStructure['lang_frontend']) ? Language::getLangUrl($reqStructure['lang_frontend']) : Language::getLangUrl();
		
		// compose the link
		$link = $reqStructure['url']['base'] . $lang_frontend . implode('/', $reqStructure['url']['path']) . (count($reqStructure['url']['path']) ? '/' : '');

		// add additional url arguments
		if ($reqStructure['get']) {
			$link .= '?' . $this->encodeUrlArgs($reqStructure['get']);
		}
		
		return $link;
	}
	
	
	/**
	 * This method makes correct URL arguments from the input (can be simple value or array). 
	 * @param mixed $input URL parameters as an array of mixed values, could be for example: 
	 *                     p = [a=>1, b=>[c=>2, d=>3]]
	 * @return string Output for the example above will be following: p[a]=1&p[b][c]=2&p[b][d]=3
	 */
	private function encodeUrlArgs($input) {
		$output = array();
		foreach ($input as $k => $v) {
			if (is_array($v)) {
				$output = array_merge($output, $this->serializeArg($k, $v));
			} else {
				$output[] = "$k" . (($v !== false) ? "=$v" : "");
			}
		}
		return implode("&", $output);
	}

	
	/**
	 * This method recursively serializes the array to the URL arguments.
	 * @see encodeUrlArgs
	 * @param string $base prefix of the argument
	 * @param mixed $input argument structure
	 * @return mixed Returns array of URL arguments.
	 */
	private function serializeArg($base, $input) {
		$output = array();
		foreach ($input as $k => $v) {
			if (is_array($v)) {
				$output = array_merge($output, $this->serializeArg($base."[".$k."]", $v));
			} else {
				$output[] = $base."[".$k."]".(($v !== false) ? "=$v" : "");
			}
		}
		return $output;
	}

	
	/**
	 * This makes the same link, as the actual request is, but arguments 
	 * can be changed (for example used to change page by paging).
	 * @param mixed $args Array of new arguments.
	 * @return string Returns a link.
	 */
	public function getSameLink($args) {
		$reqStructure = Request::getRequestStructure();
		return $this->makeLink($reqStructure, $args);
	}
	
	
	/**
	 * Check permission of the current user to the action specified by
	 * $package, $controller and $method paramaters.
	 * @param string $package
	 * @param string $controller
	 * @param string $method
	 * @return bool true if the current user has permission to the action
	 */
	public function checkPermission($package, $controller, $method) {
		if (($r = $this->getRoute($package, $controller, $method)) !== null) {
			return Permission::check($r->permission);
		} else {
			throw new Exception("Method $method does not exists in controller $controller in package $package.");
			return false;
		}
	}
}

?>

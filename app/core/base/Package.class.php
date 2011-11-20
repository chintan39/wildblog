<?php

/**
 * Package is logic collector of the models and controllers, which have something in common.
 * It is also the layer to access models and controllers through.
 * All packages are inherited from this one.
 */

class Package {
	
	private $controllers=array();
	private $models=array();		// models names (not abstract)
	private $routes=array();
	private $controllersLoaded=false;
	private $modelsLoaded=false;		// models names (not abstract)
	private $routesLoaded=false;
	private $tests=null;
	private $instance=false;

	protected $packageDirectory='';

	public $name;
	public $icon="page";
	public $order = 5;				// order of the package (0-10)
	
	public $languageSupport = false;
	
	/**
	 * Initialization
	 */
	public function __construct() {
		$this->name = str_replace("Package", "", get_class($this));
		$this->packageDirectory = file_exists(DIR_PACKAGES . $this->name) 
			? DIR_PACKAGES . $this->name . DIRECTORY_SEPARATOR 
			: DIR_PROJECT_PACKAGES . $this->name . DIRECTORY_SEPARATOR;
	}
	
	
	/**
	 * Loading controllers and models.
	 * Language support is set here (readed from database).
	 */
	public function init() {
		$this->order = Config::Get(strtoupper($this->name) . "_PACKAGE_ORDER");
		$this->languageSupport = Config::Get(strtoupper($this->name) . "_PACKAGE_LANGUAGE_SUPPORT");
		$this->loadRoutes();
	}
	
	public function setDefaultConfig() {
	
	}
	
	
	/**
	 * Reads all tests directory and includes all tests.
	 */
	public function loadTests() {
		if ($this->tests !== null)
			return;
		$this->tests = array();
		$testFiles = $this->requireFilesWithExtention($this->packageDirectory . DIR_TESTS, "php", true);
		foreach ($testFiles as $testFile) {
			$testClassName = substr($testFile["file"], 0, strrpos($testFile["file"], '.'));
			$t = new $testClassName();
			$t->package = $this->name;
			$this->tests[] = $t;
		}
	}
	
	
	/**
	 * Reads all models directory and includes all models.
	 */
	public function loadModels() {
		if ($this->modelsLoaded)
			return;
		$modelFiles = $this->requireFilesWithExtention($this->packageDirectory . DIR_MODELS, "php");
		foreach ($modelFiles as $modelFile) {
			$modelClassName = substr($modelFile["file"], 0, strrpos($modelFile["file"], '.'));
			$this->models[] = $modelClassName;
			$m = new $modelClassName();
			$m->package = $this->name;
		}
		$this->modelsLoaded = true;
	}
	
	
	/**
	 * Returns all Tests avaible.
	 * @return array of object
	 */
	public function getTests() {
		if ($this->tests === null) 
			$this->loadTests();
		return $this->tests;
	}

	
	/**
	 * Returns all Models avaible.
	 * @return array of object
	 */
	public function getModels() {
		return $this->models;
	}

	
	/**
	 * Reads all controllers directory and includes all controllers.
	 * Controlleres are stored in the array.
	 */
	public function loadControllers() {
		if ($this->controllersLoaded)
			return;
		// load system controllers
		$controllersFiles = $this->requireFilesWithExtention($this->packageDirectory . DIR_CONTROLLERS, "php");

		// load project controllers and load them
		foreach ($controllersFiles as $controllerFile) {
			$controllerClassName = substr($controllerFile["file"], 0, strrpos($controllerFile["file"], '.'));
			$controllerName = str_replace('Controller', '', $controllerClassName);
			$modelClassName = $controllerName . 'Model';
			if (!class_exists($controllerClassName)) {
				throw new Exception("Class of the controller \"$controllerName\" does not exists.");
				return null;
			}
			if (!array_key_exists($controllerName, $this->controllers))
				$this->controllers[$controllerName] = $controllerClassName;
		}
		$this->controllersLoaded = true;
	}

	
	/**
	 * Reads all routes directory and includes all routes.
	 * Routes are stored in the array.
	 */
	public function loadRoutes() {
		if ($this->routesLoaded)
			return;
		// load system routes
		$routesFiles = $this->requireFilesWithExtention($this->packageDirectory . DIR_ROUTES, "php");

		// load project routes and load them
		$this->routes = array();
		foreach ($routesFiles as $routesFile) {
			$routesClassName = substr($routesFile["file"], 0, strrpos($routesFile["file"], '.'));
			$routesName = str_replace('Routes', '', $routesClassName);
			$modelClassName = $routesName . 'Model';
			if (!class_exists($routesClassName)) {
				throw new Exception("Class of the routes \"$routesName\" does not exists.");
				return null;
			}
			$this->routes[$routesName] = $routesClassName;
		}
		$this->routesLoaded = true;
	}
	
	
	/**
	 * Returns the controllers array.
	 * @return array Controllers (array of objects)
	 */
	public function getControllers() {
		foreach (array_keys($this->controllers) as $controllerName) {
			if (is_string($this->controllers[$controllerName])) {
				$this->createController($controllerName);
			}
		}
		return $this->controllers;
	}
	
	/**
	 * Returns the controllers array.
	 * @return array Controllers (array of objects)
	 */
	public function getRoutes() {
		foreach (array_keys($this->routes) as $routesName) {
			if (is_string($this->routes[$routesName])) {
				$this->createRoutes($routesName);
			}
		}
		return $this->routes;
	}
	
	public function createController($controllerName) {
		$modelClassName = $controllerName . 'Model';
		$controllerClassName = $controllerName . 'Controller';
		$this->controllers[$controllerName] = new $controllerClassName($modelClassName, $this->name); 
	}
	
	
	public function createRoutes($routesName) {
		$modelClassName = $routesName . 'Model';
		$routesClassName = $this->routes[$routesName];
		$this->routes[$routesName] = new $routesClassName($modelClassName, $this->name); 
	}
	
	
	/**
	 * Returns the controller specified by the controller's name.
	 * This ensures the only one instance of the class.
	 * @return object Controller
	 */
	public function getController($controllerName) {
		if (!array_key_exists($this->name . $controllerName, $this->controllers)) {
			$this->createController($this->name . $controllerName);
		}
		if (!array_key_exists($this->name . $controllerName, $this->controllers)) {
			throw new Exception("The controller \"$controllerName\" does not exists.");
			return null;
		}
		return $this->controllers[$this->name . $controllerName];
	}
		

	/**
	 * Requires (includes) all files in the directory $dir with extention $extention.
	 * @param $dir string
	 * @param $extention string
	 * @return array Files required (with the directory)
	 */
	protected function requireFilesWithExtention($dir, $extention, $require=false) {
		$files = array();
		if (is_dir($dir)) {
			foreach (Utilities::getFilesWithExtention($dir, $extention) as $file) {
				if ($require)
					require_once($dir . $file);
				$files[] = array("dir" => $dir, "file" => $file);
			}
		}
		return $files;
	}
	
	
	/**
	 * Returns an icon of the package
	 * @return
	 */
	public function getIcon() {
		return $this->icon;
	}
	
	
	/**
	 * Returns the name of the package
	 * @return
	 */
	public function getName() {
		return $this->name;
	}
	
	
	
}

?>
<?php

/**
 * Including all necessary files
 */
require_once(DIR_CORE_HELPERS . 'Utilities.class.php');
require_once(DIR_CORE_FORM . 'ModelMetaItem.class.php');
require_once(DIR_CORE_FORM . 'MetaDataContainer.class.php');
require_once(DIR_CORE_HELPERS . 'Paging.class.php');
require_once(DIR_CORE_HELPERS . 'ItemCollection.class.php');
require_once(DIR_CORE_HELPERS . 'ItemQualification.class.php');
require_once(DIR_CORE_FORM . 'Form.class.php');
require_once(DIR_CORE_FORM . 'FormField.class.php');
require_once(DIR_CORE_BASE . 'Benchmark.class.php');
require_once(DIR_CORE_HELPERS . 'Restriction.class.php');
require_once(DIR_CORE_HELPERS . 'Link.class.php');
require_once(DIR_CORE_HELPERS . 'LinkCollection.class.php');
require_once(DIR_CORE_BASE . 'Package.class.php');
require_once(DIR_CORE_BASE . 'DefaultAbstractPackage.class.php');
require_once(DIR_CORE_BASE . 'AbstractTest.class.php');
require_once(DIR_CORE_BASE . 'Themes.class.php');
require_once(DIR_CORE_BASE . 'Javascript.class.php');
require_once(DIR_CORE_BASE . 'Thumbnail.class.php');
require_once(DIR_CORE_HELPERS . 'Email.class.php');
require_once(DIR_CORE_BASE . 'Language.class.php');
require_once(DIR_CORE_BASE . 'AbstractTheme.class.php');
require_once(DIR_CORE_BASE . 'Config.class.php');
require_once(DIR_LIBS . 'PhpQuickProfiler/classes/PhpQuickProfiler.php');
require_once(DIR_CORE_BASE . 'MessageBus.class.php');

require_once(DIR_PORK . 'class.dbconnection.php');
require_once(DIR_PORK . 'class.settings.php');

require_once(DIR_CORE_HELPERS . 'Permission.class.php');

require_once(DIR_SMARTY . 'Smarty.class.php');

function __autoload($class_name) {
	$package = $subfolder = $name = '';
	if (preg_match('/Controller$/', $class_name)) {
		$subfolder = str_replace('/', '', DIR_CONTROLLERS);
		$suffix = 'Controller';
	} elseif (preg_match('/Model$/', $class_name)) {
		$subfolder = str_replace('/', '', DIR_MODELS);
		$suffix = 'Model';
	} elseif (preg_match('/Routes$/', $class_name)) {
		$subfolder = str_replace('/', '', DIR_ROUTES);
		$suffix = 'Routes';
	} elseif (preg_match('/Test$/', $class_name)) {
		$subfolder = str_replace('/', '', DIR_TESTS);
		$suffix = 'Test';
	}
	if (!$subfolder) {
		return;
	}
	foreach (scandir(DIR_PACKAGES) as $packageName) {
		if ($packageName{0} == '.')
			continue;
		if (preg_match('/^' . $packageName . '(\w+)' . $suffix . '$/', $class_name, $matches)) {
			$package = $packageName . '/';
			$name = $matches[0];
		}
	}
	if ($package && $subfolder && $name) {
		$file = DIR_PACKAGES . $package . $subfolder . '/' . $name . '.php';
		if (file_exists($file)) {
			global $__wwClassesLoaded;
			$__wwClassesLoaded++;
			require_once($file);
		}
    }
}


/**
 * This is the Environment class. Environment is the first layer between 
 * application and the neighbourhood (HTTP protocol on one side, and user 
 * on the other side).
 * In Environment object are saved all models and controllers names and 
 * Smarty templates engine has the only one instance in here.
 */
class Environment {
	
	static public $smarty;				// smarty templates engine reference 
	static public $packages=array();		// packages objects list
	static public $allowedPackages=array();		// packages objects list allowed
	static public $profiler = null;
	
	
	/**
	 * Initialization of the Environmetnt
	 * Packages (Controllers and Models) classes are included and necessary 
	 * utilities are initialized (Smarty template engine).
	 */
	static public function init() {
		$benchmark = new Benchmark();
		self::initSmarty();
		self::$smarty->load_filter('output','specialinfoondebug');
		self::$smarty->load_filter('output','addautolinks');
		self::$smarty->register_block('tp','Utilities__smarty_translate_p');
		self::$smarty->register_block('tg','Utilities__smarty_translate_g');
		self::$smarty->register_block('tu','Utilities__smarty_translate_u');
		self::$smarty->register_outputfilter('Javascript__addHTML');
		self::$smarty->register_outputfilter('MessageBus__exportMessages');

		self::loadPackages();
		$permission = new Permission();

		self::loadConfig();

		self::initPackages();
		Themes::loadThemes();
		
		// load languages and init
		Language::init();

	}
	
	
	/**
	 * Initialization of the Smarty template engine.
	 */
	static private function initSmarty() {
		self::$smarty = new Smarty();
		self::$smarty->template_dir = DIR_SMARTY_TEMPLATES;
		self::$smarty->compile_dir = DIR_SMARTY_TEMPLATES_C;
		self::$smarty->cache_dir = DIR_SMARTY_CACHE;
		self::$smarty->config_dir = DIR_SMARTY_CONFIG;
		define("COMMON_IMAGES_PATH", str_replace("[theme]", "Common", DIR_SMARTY_THEME_IMAGES));
		self::$smarty->assign("commonImagesPath", str_replace("[theme]", "Common", DIR_SMARTY_THEME_IMAGES));
		self::$smarty->assign("iconsPath", DIR_ICONS_IMAGES_DIR_THUMBS_URL);
	}
	
	
	/**
	 * Reads all packages directory and includes all package's models and controllers.
	 */
	static private function loadPackages() {
		self::$packages = array();
		self::loadPackagesDir(DIR_PACKAGES);
		self::loadPackagesDir(DIR_PROJECT_PACKAGES);
	}
	
	/**
	 * Reads all packages directory and includes all package's models and controllers.
	 */
	static private function loadPackagesDir($dir) {
		if (!is_dir($dir))
			return;
		// load all packages' classes
		foreach (scandir($dir) as $packageName) {
			$packageClassFile = $dir . $packageName . DIRECTORY_SEPARATOR . $packageName . "Package.php";
			if (is_dir($dir . $packageName) && file_exists($packageClassFile)) {
				require_once($packageClassFile);
				$packageClassName = $packageName . "Package";
				self::$packages[$packageName] = new $packageClassName();
				self::$packages[$packageName]->setDefaultConfig();
			}
		}
	}
	
	/**
	 * Initialization of the packages (if they are allowed).
	 * This means loading controllers and models from the package.
	 */
	static private function initPackages() {
		foreach (self::$packages as $package) {
			if (Config::Get(strtoupper($package->name) . '_PACKAGE_ALLOW')) {
				$package->init();
				self::$allowedPackages[] = $package;
			}
		}
	}

	
	/**
	 * Loading project-specific config from DB for the current project 
	 * and for the current user.
	 * User-specific config items override the project-specific and
	 * project-specific override the default project config.
	 */
	static private function loadConfig() {
		require_once(DIR_PACKAGES . 'Base' . DIRECTORY_SEPARATOR . DIR_MODELS . 'BaseConfigModel.php');
		$configModel = new BaseConfigModel();
		$config = $configModel->LoadConfig();
		if ($config) {
			foreach ($config as $configItem) {
				if (strcasecmp($configItem->text, 'true') == 0) {
					$value = true;
				} elseif (strcasecmp($configItem->text, 'false') == 0) {
					$value = false;
				} else {
					$value = $configItem->text;
				}
				Config::Set($configItem->key, $value, $configItem->description, null, null, $configItem->id);
			}
		}
		
		// personal user config
		$user = Permission::getActualUserInfo();
		if ($user) {
			foreach (explode("\n", trim($user->private_config)) as $private_config) {
				$value = explode(":", trim($private_config));
				if (count($value) != 2) {
					continue;
				}
				Config::Set(trim($value[0]), trim($value[1]));
			}
		}
	}
	
	
	/**
	 * Returns the packages array.
	 * @return array Packages array (array of objects)
	 */
	static public function getPackages() {
		return self::$allowedPackages;
	}


	/**
	 * Returns array of all controllers.
	 * @return array Controllers array (array of objects)
	 */
	static public function getControllers() {
		$controllers = array();
		foreach (self::$packages as $package) {
			$controllers = array_merge($controllers, $package->getControllers());
		}
		return $controllers;
	}


	/**
	 * Returns array of all routes.
	 * @return array Routes array (array of objects)
	 */
	static public function getRoutes() {
		$routes = array();
		foreach (self::$packages as $package) {
			$routes = array_merge($routes, $package->getRoutes());
		}
		return $routes;
	}

	
	/**
	 * Returns the controller specified by the controller's name.
	 * This ensures the only one instance of the class.
	 * @return object Controller
	 */
	static public function getPackage($packageName) {
		if (!array_key_exists($packageName, self::$packages)) {
			throw new Exception("The package \"$packageName\" does not exists.");
			return null;
		}
		return self::$packages[$packageName];
	}
		
	
}

?>

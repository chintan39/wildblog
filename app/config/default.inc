<?php

/**
 * Common global constants definitions for all projects.
 */

date_default_timezone_set('Europe/Prague');

// version of the application
define('APP_VERSION', '0.4.7');
define('APP_GENERATOR_NAME', 'WildBlog');

define('DIR_APP', 'app/');
define('DIR_PROJECT_BASE', '../');
define('BASE_PATH', preg_replace('/app\/config$/', '', __dir__));

// $_GET['__project__'] is set by .htaccess to support multiple project
if (isset($_GET['__project__']) && preg_match('/^[a-zA-Z0-9\/\-_]*$/', $_GET['__project__']))
	define('DIR_PROJECT_NAME', $_GET['__project__']);
else
	define('DIR_PROJECT_NAME', 'project');

if (!is_dir(DIR_PROJECT_BASE . DIR_PROJECT_NAME))
	die('Project path "' . DIR_PROJECT_BASE . DIR_PROJECT_NAME . '" does not exist.');

define('DIR_PROJECT_PATH', DIR_PROJECT_BASE . DIR_PROJECT_NAME . '/');
define('DIR_PROJECT_URL', 'project' . '/');
define('DIR_PACKAGES', DIR_APP . 'packages/');
define('DIR_PROJECT_PACKAGES', DIR_PROJECT_PATH . 'packages/');
define('DIR_CONFIG', DIR_APP . 'config/');
define('DIR_PROJECT_CONFIG', DIR_PROJECT_PATH . 'config/');
define('SUBDIR_MEDIA', 'media/');
define('SUBDIR_THUMBS', '.thumbs/');
define('DIR_PROJECT_URL_MEDIA', SUBDIR_MEDIA);
define('DIR_PROJECT_URL_MEDIA_THUMBS', DIR_PROJECT_URL_MEDIA . SUBDIR_THUMBS);
define('DIR_PROJECT_PATH_MEDIA', DIR_PROJECT_PATH . SUBDIR_MEDIA);
define('DIR_PROJECT_PATH_MEDIA_THUMBS', DIR_PROJECT_PATH_MEDIA . SUBDIR_THUMBS);
define('DIR_PROJECT_CACHE', DIR_PROJECT_PATH . 'cache/');
define('DIR_UPDATE_SCRIPTS', DIR_APP . 'scripts/');
define('UPDATE_SCRIPTS_BASE', DIR_UPDATE_SCRIPTS . 'updateBase.php');

// version file
define('VERSION_FILE_NAME', 'version');
define('VERSION_FILE', DIR_PROJECT_CONFIG . VERSION_FILE_NAME);

// load system config
// application directories
define('DIR_CORE', DIR_APP . 'core/');
define('DIR_CORE_BASE', DIR_CORE . 'base/');
define('DIR_CORE_HELPERS', DIR_CORE . 'helpers/');
define('DIR_CORE_FORM', DIR_CORE . 'form/');
define('DIR_CORE_ROUTING', DIR_CORE . 'routing/');
define('DIR_LIBS', DIR_APP . 'libs/');
define('DIR_MODELS', 'models/');
define('DIR_TESTS', 'tests/');
define('DIR_CONTROLLERS', 'controllers/');
define('DIR_ROUTES', 'routes/');
define('DIR_ABSTRACT', 'abstract/');

define('PACKAGE_ABSTRACT', 'Abstract');

define('DIR_THEMES', DIR_APP . 'themes/');
define('DIR_COMMON', DIR_THEMES . 'Common/');
define('DIR_ICONS_IMAGES_DIR_ORIGINAL', DIR_COMMON . 'images/ico/');
define('DIR_ICONS_IMAGES_DIR_THUMBS_PATH', DIR_PROJECT_PATH_MEDIA . '.ico/');
define('DIR_ICONS_IMAGES_DIR_THUMBS_URL', DIR_PROJECT_URL_MEDIA . '.ico/');
	
// pork-db application directory
define('DIR_PORK', DIR_LIBS . 'pork.dbobject/');

// smarty templates engine directories
define('DIR_SMARTY', DIR_LIBS . 'smarty/');
define('DIR_SMARTY_TEMPLATES', '');
define('DIR_SMARTY_THEME_TEMPLATES', DIR_THEMES . '[theme]/templates/[package].' );
define('DIR_SMARTY_THEME_CSS', DIR_THEMES . '[theme]/css/' );
define('DIR_SMARTY_THEME_IMAGES', DIR_THEMES . '[theme]/images/' );
define('DIR_SMARTY_THEME_JS', DIR_THEMES . '[theme]/js/' );
define('DIR_SMARTY_TEMPLATES_C', DIR_PROJECT_CACHE . 'templates_c/');
define('DIR_SMARTY_CACHE', DIR_PROJECT_CACHE . 'cache/');
define('DIR_SMARTY_CONFIG', DIR_APP . 'config/');

// models and controllers cache dir
define('DIR_MODELS_CACHE', DIR_PROJECT_CACHE . 'models/');
define('DIR_CONTROLLERS_CACHE', DIR_PROJECT_CACHE . 'controllers/');

// db default config
define('DB_CONFIG_FILE_NAME', 'db.inc');
define('DB_CONFIG_FILE', DIR_PROJECT_CONFIG . DB_CONFIG_FILE_NAME);

// captcha fonts
define('DIR_CAPTCHA_FONTS', DIR_LIBS . 'captcha/fonts/');

// URL part and nice URL part format regular expression
define('REGEXP_URL_PART', '/^[a-zA-Z0-9-~\.]+$/');
define('REGEXP_URL_PART_NICE', '/^[a-z0-9-]+$/');

// e-mail format regular expression and other formats
define('REGEXP_EMAIL', '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/');
define('REGEXP_LINK', '/^(http|https|ftp)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?\/?([a-zA-Z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*[^\.\,\)\(\s]$/');
define('REGEXP_NUMBER', '/^([\-]?(\d)+[\.]?(\d)*)?$/');
define('REGEXP_DATE', '/^\d{4}-\d{2}-\d{2}$/');
define('REGEXP_TIME', '/^\d{2}:\d{2}:\d{2}$/');
define('REGEXP_COLOR_RGB_HEXA', '/^#[a-zA-Z0-9]{6}$/');

// regular expression expressing that part of the url is an attribute of the object
// it means, that the expression matches pattern '[attribute_value]'
define('REGEXP_URL_ATTR', '/^(\[)([^\]]*)(\])$/');

// regular expression expressing that the part of the url must correspond with a 'regular expression'
// it means, that the part of the url matches pattern '{attribute_value}'
// if there are brackets in the regular expression, the content of the brackets can be named this way:
// /^there($myName=[\d{2}])itis$/ ... myName will be take away and used in the controller to control which value it is
define('REGEXP_URL_IS_REGEXP', '/^(#)([^#]*)(#)$/');
define('REGEXP_URL_REGEXP_BRACKETS_EXTRACTION', '/\(([^\)]*)\)/');
define('REGEXP_URL_REGEXP_IDENTIFICATOR_EXTRACTION', '/^\$([\w]+)=(.*)$/');

// we can insert parameters as usual variables in translated texts
// this is regular expression to parse these variables
define('REGEXP_TRANSLATE_PARAMETER', '/#\$(\w+)#/');

// regular expression to simple text
define('REGEXP_TEXT_FORMAT', '/^[a-zA-Z0-9-%~\. ]*$/');

// regular expression to number
define('REGEXP_NUMBER_FORMAT_CS', '/^[\-]?(\d{1,3})([\. ]\d{3})*(,\d*)?$/');
define('REGEXP_NUMBER_FORMAT_EN', '/^[\-]?(\d{1,3})([, ]\d{3})*(\.\d*)?$/');

// regular expression thumbnail
define('REGEXP_THUMB_FORMAT', '/^(.*)\/([^\/]*)\.([^\/\.]*)$/');

// dictionary settings
define('DICTIONARY_KEY_LENGTH', 255);

// sorting direction
define('SORTING_ASC', 'asc');
define('SORTING_DESC', 'desc');

// project statuses
define('PROJECT_NOT_INSTALLED', 0);
define('PROJECT_DB_NEED_CHECK', 1);
define('PROJECT_READY', 2);

// general controllers config
define('DEFAULT_PAGING_LIMIT', 30);

// maximum size of uploaded images
define('DEFAULT_UPLOAD_IMAGE_WIDTH', 1100);
define('DEFAULT_UPLOAD_IMAGE_HEIGHT', 700);

// symbolic value to use to indicate that value in request should be preserved
// this value can be used as a value in url, but will be preserved always
define('PRESERVE_VALUE', '_preserve_');

// captcha length
define('CAPTCHA_LENGTH', 5);
define('CAPTCHA_WIDTH', 150);
define('CAPTCHA_HEIGHT', 75);

// request type param name
define('REQUEST_TYPE_PARAM_NAME', '__request_type__');

?>

<?php

/**
 * Install the WildBlog project
 * This file will generate a form to set the basic info about project 
 * and project's database. After all values are filled in and are correct
 * config files are created from their templates.
 * After all files are created, user will be redirected to /db_init/ page, 
 * where database structure will be creted. 
 * After DB is created, INIT_PASSED is set to 1.
 */
 
define('CONFIG_FILE_INPUT', 'app/config/config.php.template');
define('DB_CONFIG_FILE_INPUT', 'app/config/db.inc.template');
define('ERROR_HANDLER_CONFIG_FILE_INPUT', 'app/config/error_handler_config.ini.template');
define('CONFIG_FILE_OUTPUT', 'project/config/config.php');
define('DB_CONFIG_FILE_OUTPUT', 'project/config/db.inc');
define('ERROR_HANDLER_CONFIG_FILE_OUTPUT', 'project/config/error_handler_config.ini');
define('LOG_DIR', 'log');
define('CACHE_DIR', 'cache');
define('BACKUP_DIR', 'backup');
define('CACHE_CONTROLLERS_DIR', 'cache/controllers');
define('CACHE_MODELS_DIR', 'cache/models');
define('CACHE_TEMPLATES_DIR', 'cache/templates_c');
define('IMAGES_DIR', (isset($_GET['__project__']) ? $_GET['__project__'] : 'project') . '/images');
define('FILES_DIR', (isset($_GET['__project__']) ? $_GET['__project__'] : 'project') . '/files');
define('MEDIA_DIR', (isset($_GET['__project__']) ? $_GET['__project__'] : 'project') . '/media');

function parseUrl() {
	$url = array();
	$url['protocol'] = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$url['project_url'] = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
	$url['project_url'] = preg_replace('/^\//', '', $url['project_url']);
	$url['project_url'] = preg_replace('/\/$/', '', $url['project_url']);
	$url['base'] = $url['protocol'] . $_SERVER['HTTP_HOST'] . '/' . $url['project_url'] . '/';
	$qMarkPos = strpos($_SERVER['REQUEST_URI'], '?');
	$url['pathRaw'] = ($qMarkPos !== false) ? substr($_SERVER['REQUEST_URI'], 0, $qMarkPos) : $_SERVER['REQUEST_URI'];
	if ($url['pathRaw'] && !preg_match('/\/$/', $url['pathRaw'])) {
		$url['pathRaw'] = $url['pathRaw'] . '/';
	}
	if ($url['project_url']) {
		$url['pathRaw'] = preg_replace('/^\/' . $url['project_url'] . '\//', '', $url['pathRaw']);
	}
	$url['path'] = array();
	foreach (explode('/', $url['pathRaw']) as $p) {
		if ($p) {
			$url['path'][] = $p;
		}
	}
	return $url;
}


function checkValues(&$values, &$errors, &$actions, &$adminActions, &$languages) {
	if (!$values['project_name']) {
		$errors[] = 'Name of the project cannot be empty.';
	}
	if (!$values['project_desc']) {
		$errors[] = 'Description of the project cannot be empty.';
	}
	if (!$values['admin_email'] || !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/', $values['admin_email'])) {
		$errors[] = 'Admin\'s email cannot be empty and has to be in right format.';
	}
	$values['default_action'] = (int)$values['default_action'];
	if ($values['default_action'] < 0 || $values['default_action'] >= count($actions)) {
		$errors[] = 'Default action cannot be empty.';
	}
	$values['default_admin_action'] = (int)$values['default_admin_action'];
	if ($values['default_admin_action'] < 0 || $values['default_admin_action'] >= count($adminActions)) {
		$errors[] = 'Default admin action cannot be empty.';
	}
	$values['default_language'] = (int)$values['default_language'];
	if ($values['default_language'] < 0 || $values['default_language'] >= count($languages)) {
		$errors[] = 'Default language cannot be empty.';
	}
	if ($values['database_server'] && $values['database_user']
		&& $values['database_password'] && $values['database_name']) {
		$dbh = mysql_connect($values['database_server'], $values['database_user'], $values['database_password']);
		if (!$dbh) {
			$errors[] = 'Could not connect to database. Please check the connection data.';
		} else {
			if (!mysql_select_db($values['database_name'], $dbh)) {
				$errors[] = 'Could not select the database.';
			}
		}
	} else {
		$errors[] = 'Database data must be filled.';
	}
	
	if (!is_dir(LOG_DIR)) {
		mkdir(LOG_DIR, 0777);
		if (!is_dir(LOG_DIR)) {
			$errors[] = 'Log directory "' . LOG_DIR . '" cannot be created.';
		}
	}
	
	if (!is_dir(BACKUP_DIR)) {
		mkdir(BACKUP_DIR, 0777);
		if (!is_dir(BACKUP_DIR)) {
			$errors[] = 'Backup directory "' . BACKUP_DIR . '" cannot be created.';
		}
	}

	if (!is_dir(CACHE_DIR)) {
		mkdir(CACHE_DIR, 0777);
		if (!is_dir(CACHE_DIR)) {
			$errors[] = 'Cache directory "' . CACHE_DIR . '" cannot be created.';
		}
	}
	if (!is_dir(CACHE_CONTROLLERS_DIR)) {
		mkdir(CACHE_CONTROLLERS_DIR, 0777);
		if (!is_dir(CACHE_CONTROLLERS_DIR)) {
			$errors[] = 'Cache directory "' . CACHE_CONTROLLERS_DIR . '" cannot be created.';
		}
	}
	if (!is_dir(CACHE_MODELS_DIR)) {
		mkdir(CACHE_MODELS_DIR, 0777);
		if (!is_dir(CACHE_MODELS_DIR)) {
			$errors[] = 'Cache directory "' . CACHE_MODELS_DIR . '" cannot be created.';
		}
	}
	if (!is_dir(CACHE_TEMPLATES_DIR)) {
		mkdir(CACHE_TEMPLATES_DIR, 0777);
		if (!is_dir(CACHE_TEMPLATES_DIR)) {
			$errors[] = 'Cache directory "' . CACHE_TEMPLATES_DIR . '" cannot be created.';
		}
	}
	if (!is_dir(IMAGES_DIR)) {
		mkdir(IMAGES_DIR, 0777);
		if (!is_dir(IMAGES_DIR)) {
			$errors[] = 'Images directory "' . IMAGES_DIR . '" cannot be created.';
		}
	}
	if (!is_dir(FILES_DIR)) {
		mkdir(FILES_DIR, 0777);
		if (!is_dir(FILES_DIR)) {
			$errors[] = 'Files directory "' . FILES_DIR . '" cannot be created.';
		}
	}
	if (!is_dir(MEDIA_DIR)) {
		mkdir(MEDIA_DIR, 0777);
		if (!is_dir(MEDIA_DIR)) {
			$errors[] = 'Media directory "' . MEDIA_DIR . '" cannot be created.';
		}
	}
	
	if (is_dir(LOG_DIR) && !is_writable(LOG_DIR)) {
		$errors[] = 'Log directory "' . LOG_DIR . '" is not writable.';
	}
	if (is_dir(CACHE_DIR) && !is_writable(CACHE_DIR)) {
		$errors[] = 'Cache directory "' . CACHE_DIR . '" is not writable.';
	}
	if (is_dir(IMAGES_DIR) && !is_writable(IMAGES_DIR)) {
		$errors[] = 'Images directory "' . IMAGES_DIR . '" is not writable.';
	}
	if (is_dir(FILES_DIR) && !is_writable(FILES_DIR)) {
		$errors[] = 'Files directory "' . FILES_DIR . '" is not writable.';
	}
	
	return count($errors) == 0;
}

function replaceFile($fileInput, $fileOutput, $repl) {
	$content = file_get_contents($fileInput);
	$content = str_replace(array_keys($repl), array_values($repl), $content);
	return file_put_contents($fileOutput, $content);
}

function handleRequest(&$values, $url) {
	
	$actions = array(
		'Blog::Posts::actionPostsList' => 'List of blog posts',
		'Basic::Articles::actionHomepageArticle' => 'Article tagged as homepage',
		'Commodity::Products::actionProductsList' => 'List of products',
		);
	
	$adminActions = array(
		'auto|Blog::Posts::actionListing' => 'List of blog posts',
		'auto|Basic::Articles::actionListing' => 'List of articles',
		'auto|Commodity::Products::actionListing' => 'List of products',
		);
	
	$languages = array('cs' => 'Čeština', 'en' => 'English', 'de' => 'Němčina', 'sk' => 'Slovenčina');
	$errors = array();
	if (isset($_POST['submit'])) {
		$values['project_name'] = $_POST['project_name'];
		$values['project_desc'] = $_POST['project_desc'];
		$values['admin_email'] = $_POST['admin_email'];
		$values['default_action'] = $_POST['default_action'];
		$values['default_admin_action'] = $_POST['default_admin_action'];
		$values['default_language'] = $_POST['default_language'];
		$values['action_options'] = array_values($actions);
		$values['admin_action_options'] = array_values($adminActions);
		$values['language_options'] = array_values($languages);
		$values['database_server'] = $_POST['database_server'];
		$values['database_user'] = $_POST['database_user'];
		$values['database_password'] = $_POST['database_password'];
		$values['database_name'] = $_POST['database_name'];
		$values['database_prefix'] = $_POST['database_prefix'];
		$values['tables_exist'] = isset($_POST['tables_exist']);
		
		if (checkValues($values, $errors, $actions, $adminActions, $languages)) {
			$action_keys = array_keys($actions);
			$admin_action_keys = array_keys($adminActions);
			$languages_keys = array_keys($languages);
			if (!replaceFile(CONFIG_FILE_INPUT, CONFIG_FILE_OUTPUT, array(
				'___default_action___' => $action_keys[$values['default_action']],
				'___default_admin_action___' => $admin_action_keys[$values['default_admin_action']],
				'___default_language___' => $languages_keys[$values['default_language']],
				'___admin_email___' => $values['admin_email'],
				'___project_url___' => $url['project_url'],
				'___project_title___' => $values['project_name'],
				'___project_description___' => $values['project_desc'],
				'___project_status___' => ($values['tables_exist'] ? 'PROJECT_READY' : 'PROJECT_NOT_INSTALLED'),
				))) {
				$values['errors'][] = 'Config file could not be eddited.';
			}
			
			if (!replaceFile(DB_CONFIG_FILE_INPUT, DB_CONFIG_FILE_OUTPUT, array(
				'___database_server___' => $values['database_server'],
				'___database_user___' => $values['database_user'],
				'___database_password___' => $values['database_password'],
				'___database_name___' => $values['database_name'],
				'___database_prefix___' => $values['database_prefix'],
				))) {
				$values['errors'][] = 'Database config file could not be eddited.';
			}
			
			if (!replaceFile(ERROR_HANDLER_CONFIG_FILE_INPUT, ERROR_HANDLER_CONFIG_FILE_OUTPUT, array(
				'___project_base___' => $url['base'],
				'___admin_email___' => $values['admin_email'],
				))) {
				$values['errors'][] = 'Error handler file could not be eddited.';
			}
			
			if (count($values['errors'])) {
				return $values;
			}
			
			// OK - redirect
			$link = $url['base'];
			header('HTTP/1.1 302 Found');
			header('Location: ' . $link);
			exit();
		} else {
			$values['errors'] = $errors;
		}
	} else {
		$values['project_name'] = 'Project Name';
		$values['project_desc'] = 'Description of the project using short text.';
		$values['admin_email'] = 'your@email.com';
		$values['default_action'] = 0;
		$values['default_admin_action'] = 0;
		$values['default_language'] = 0;
		$values['action_options'] = array_values($actions);
		$values['admin_action_options'] = array_values($adminActions);
		$values['language_options'] = array_values($languages);
		$values['database_server'] = 'localhost';
		$values['database_user'] = 'wildnew';
		$values['database_password'] = '';
		$values['database_name'] = 'wildnew';
		$values['database_prefix'] = 'wildblog_';
		$values['tables_exist'] = false;
		$values['errors'] = array();
	}
	return $values;
}

function printErrors($errors) {
	if (count($errors)) {
		echo '<ul class="errors">';
		foreach ($errors as $e) {
			echo '<li>' . $e . '</li>';
		}
		echo '</ul>';
	}
}

//error_reporting(E_ERROR);
$url = parseUrl();
$step = (isset($url['path'][0]) ? $url['path'][0] : '1');
$values = array();

switch ($step) {
	case '1':
		handleRequest($values, $url);
	break;
}


echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
  <head>
  <title>WildBlog installer</title>
  <meta name="description" content="Popis projektu" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="robots" content="index, follow" />
  <meta name="rating" content="general" />
  <meta name="author" content="Jan Horák; mailto:horak.jan@centrum.cz" />
  <meta name="generator" content="WildBlog version 0.1.debug" />
  <meta name="copyright" content="Jan Horák" />
  <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo $url['base']; ?>app/themes/Common/css/install.css" />
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo $url['base']; ?>app/themes/Common/images/favicon.ico" />
  </head>
  <body>

<div id="page">

<h1>WildBlog instal</h1>

<img src="<?php echo $url['base']; ?>app/themes/Common/images/logo.png" alt="" class="logo" />

<form action="<?php echo $url['base'] . $url['pathRaw']; ?>" method="post" class="cleanform">

<?php

printErrors($values['errors']);

switch ($step) {
	case '1':
		?>

<div class="header">
	<p class="description">Please, fill in basic info about project.</p>
</div>

	<div class="line"><label for="form_project_name">Project name<span class="small">Short name to be used to in descriptions</span></label><input id="form_project_name" name="project_name" value="<?php echo htmlspecialchars($values['project_name']); ?>" type="text" /><div class="clear"></div></div>

	<div class="line"><label for="form_project_desc">Web description<span class="small">Longer text will be used as default description</span></label><textarea class="lite" id="form_project_desc" name="project_desc" cols="80" rows="13"><?php echo htmlspecialchars($values['project_desc']); ?></textarea><div class="clear"></div></div>
	
	<div class="line"><label for="form_admin_email">Admin e-mail<span class="small">It will be used for sending critical errors and as the first user with admin paermissions</span></label><input id="form_admin_email" name="admin_email" value="<?php echo htmlspecialchars($values['admin_email']); ?>" type="text" /><div class="clear"></div></div>
	
	<div class="line"><label for="form_default_action">Default action<span class="small">It will be used as homepage</span></label>
	<select name="default_action" id="form_default_action">
	<?php
	foreach ($values['action_options'] as $k => $v) {
		$checked = ($values['default_action'] == $k) ? ' selected="selected"' : '';
		echo '<option value="' . $k . '"' . $checked . '>' . $v . '</option>' . "\n";
	}
	?>
	</select><div class="clear"></div></div>

	<div class="line"><label for="form_default_admin_action">Default admin action<span class="small">It will be used as starting page in administration</span></label>
	<select name="default_admin_action" id="form_default_admin_action">
	<?php
	foreach ($values['admin_action_options'] as $k => $v) {
		$checked = ($values['default_admin_action'] == $k) ? ' selected="selected"' : '';
		echo '<option value="' . $k . '"' . $checked . '>' . $v . '</option>' . "\n";
	}
	?>
	</select><div class="clear"></div></div>

	<div class="line"><label for="form_default_language">Default language<span class="small">It will be used for frontend and backend</span></label>
	<select name="default_language" id="form_default_language">
	<?php
	foreach ($values['language_options'] as $k => $v) {
		$checked = ($values['default_language'] == $k) ? ' selected="selected"' : '';
		echo '<option value="' . $k . '"' . $checked . '>' . $v . '</option>' . "\n";
	}
	?>
	</select><div class="clear"></div></div>

	<div class="line"><label for="form_database_server">Database server<span class="small">Server name withou http</span></label><input id="form_database_server" name="database_server" value="<?php echo htmlspecialchars($values['database_server']); ?>" type="text" /><div class="clear"></div></div>
	
	<div class="line"><label for="form_database_user">Database user<span class="small">Username to access the database as a visitor</span></label><input id="form_database_user" name="database_user" value="<?php echo htmlspecialchars($values['database_user']); ?>" type="text" /><div class="clear"></div></div>
	
	<div class="line"><label for="form_database_password">Database password<span class="small">Password to access the database as a visitor</span></label><input id="form_database_password" name="database_password" value="<?php echo htmlspecialchars($values['database_password']); ?>" type="password" /><div class="clear"></div></div>
	
	<div class="line"><label for="form_database_name">Database name<span class="small">Name of the MySQL database</span></label><input id="form_database_name" name="database_name" value="<?php echo htmlspecialchars($values['database_name']); ?>" type="text" /><div class="clear"></div></div>

	<div class="line"><label for="form_database_prefix">Database prefix<span class="small">Prefix of all tables</span></label><input id="form_database_prefix" name="database_prefix" value="<?php echo htmlspecialchars($values['database_prefix']); ?>" type="text" /><div class="clear"></div></div>

	<div class="line"><label for="form_tables_exist">Tables exist already<span class="small">Check if there are tables already in DB</span></label><input id="form_tables_exist" name="tables_exist" value="1" <?php echo ($values['tables_exist'] ? 'checked="checked" ' : ''); ?> type="checkbox" class="checkbox" /><div class="clear"></div></div>

	<div class="float-right">
	<input class="button positive add" id="form_submit" name="submit" value="Odeslat" onclick="return changeTextAndDisable(this, 'Odesílám...');" type="submit" /> 
	<input class="button negative delete" id="form_cancel" name="cancel" value="Zrušit" type="submit" /> 
	<div class="clear"></div>
	
<?php
	break;
	case '2':
	?>
		<p class="confirm">Project has been installed successfully.</p>
	<?php

	break;
}
?>

</div>
<div class="clear"></div>
</form>


</div>
</body>
</html>

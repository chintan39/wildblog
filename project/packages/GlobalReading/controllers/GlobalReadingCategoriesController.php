<?php

class GlobalReadingCategoriesController extends AbstractPagesController {
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and controller actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter(&$reqRoutes) {

		AbstractAdminController::setRouter($this, $reqRoutes);
		
		$reqRoutes->registerAction($this, 'actionFormCreate', 
			'formCreate', Themes::FRONT_END, 
			Permission::$ALL,
			'form-create/[url]/$');
		
		$reqRoutes->registerSubaction($this, 'subactionCategoriesMenu', 
			'part.categoriesMenu', Themes::FRONT_END, 
			Permission::$ALL);
		
	}
	


	public function subactionCategoriesMenu() {
		global $benchmark;
		$benchmark->log("Begin of creating GlobalReadingCategoriesController::subactionCategoriesMenu");

		$categoriesMenu = $this->loadCache('categoriesMenu');
		if (!$categoriesMenu) {
			$categoriesMenu = new ItemCollection('categoriesMenu', $this, null, 'getCollectionItems');
			$categoriesMenu->setLinks('actionFormCreate');
			$categoriesMenu->loadCollection();
			$categoriesMenu->addLinks();
			$this->saveCache('categoriesMenu', $categoriesMenu, array('GlobalReadingCategoriesModel'));
		}
		$this->assign($categoriesMenu->getIdentifier(), $categoriesMenu);

		$benchmark->log("End of creating GlobalReadingCategoriesController::subactionCategoriesMenu");
	}
	
	
	/**
	 * Request handler
	 * Creating a new form. 
	 */
	public function actionFormCreate($args) {
		global $benchmark, $req;
		if ( isset($_POST['mode']) && isset($_POST['tableContent'])) {
			
			$_SESSION['GlobalReading_tableContent_'.$args->id] = $_POST['tableContent'];
			$_SESSION['GlobalReading_lastFormId'] = $_POST['lastFormId'];

			// ratios to 
			$heightRatio = '1.5';
			$widthRatio = '1.5';
            if ($_POST['mode'] == 'pdf') {
            	$lineHeight = '1.65em';
				$textRatio = '1.4';
				$textDefault = '18';
				$imgRatio = '1.5';
            } else {
            	$lineHeight = '1.2em';
				$textRatio = '1.4';
				$textDefault = '18';
				$imgRatio = '1.35';
            }
            
			$tableContent = $_POST['tableContent'];
			
			if ( get_magic_quotes_gpc() ) {
				$tableContent = stripslashes($tableContent);
			}
			
			// make all fonts bigger
			$tableContent = preg_replace_callback('/(font-size:\s+)(\d+)/i', create_function(
				'$matches',
				'return $matches[1] . round('.$textRatio.' * $matches[2]);'
            ), $tableContent);
            
            // convert all heights from % to px
			$tableContent = preg_replace_callback('/(height:\s+)(\d+)px/i', create_function(
				'$matches',
				'return $matches[1] . round('.$heightRatio.' * $matches[2]) . \'px\';'
            ), $tableContent);
            
            // convert all widths from % to px
			$tableContent = preg_replace_callback('/(width:\s+)(\d+)px/i', create_function(
				'$matches',
				'return $matches[1] . round('.$widthRatio.' * $matches[2]) . \'px\';'
            ), $tableContent);
            // convert all widths from % to px
			$tableContent = preg_replace_callback('/(\d+)x(\d+)(\w)_thumb_/i', create_function(
				'$matches',
				'return round('.$imgRatio.' * $matches[1]) . "x" . round('.$imgRatio.' * $matches[2]) . $matches[3] . "_thumb_";'
            ), $tableContent);
            
            // wrap the content with correct HTML
			$tableContent = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>'.tp('Global reading').'</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style> 
* {
	padding: 0px;
	margin: 0px;
	border: 0;
}

html, body {
	background: #ffffff;
	color: #000000;
	font-size: '.$textDefault.'px;
	line-height: '.$lineHeight.';
	font-family: Verdana, "Geneva CE", lucida, sans-serif;
}

table tr td {
	verticle-align: center;
}

table {
	border-collapse: collapse;
}

table.blackborder tr td {
	border: 1px solid black;
}

img {
}

ul, ol {
	margin-left: 30px;
}
</style>
</head>
<body>
' . $tableContent . (($_POST['mode'] == 'print') ? '<script type="text/javascript">window.print();</script>' : '') . '</body></html>';

			if ($_POST['mode'] == 'pdf') {
				require_once(DIR_LIBS . 'mpdf/mpdf.php');
				//$old_limit = ini_set('memory_limit', '128M');
				
				// if memory problems, we can use core fonts ('c' as first parameter)
				$mpdf = new mPDF('', 'A4', '', '', 18, 18, 10, 10, 16, 13); 
				
				$mpdf->SetDisplayMode('fullpage');
				
				$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
				
				$mpdf->WriteHTML($tableContent);
				
				// export as attachement
				$mpdf->Output('export-' . date('ymd') . '.pdf', 'D');
				exit; 
			} elseif ($_POST['mode'] == 'print') {
				echo $tableContent;
				exit;
			}
		}
		
		$tmp = new GlobalReadingFormsModel();
		$category = $args;
		$forms = $tmp->getItems('GlobalReadingFormsModel', array('category = ?'), array($category->id));
		$this->assign('forms', $forms);
		$this->assign('createFormId', $category->id);
		$this->assign('category', $category);
		
		Javascript::addFile($req->url['base'] . DIR_LIBS . 'ImageManager/ImageManager.js');
		Javascript::addWysiwyg('form_text', Javascript::WYSIWYG_MICRO, array('cssFile' => 'createform.css'));
		
		if (isset($_SESSION['GlobalReading_tableContent_'.$args->id])) {
			$this->assign('tableContentPreloaded', $_SESSION['GlobalReading_tableContent_'.$args->id]);
		}
		
		if (isset($_SESSION['GlobalReading_lastFormId'])) {
			$this->assign('lastFormId', $_SESSION['GlobalReading_lastFormId']);
		}
		
		
		// assign to template
		$this->assign('title', tg('Form create'));
		
	}

	/**
	 * Returns all pages' urls, that should be in Sitemap.
	 */
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array(), array('actionFormCreate' => tg('Form create')));
	}	
	
}

?>

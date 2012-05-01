{if $actualUserInfo and $actualUserInfo->id}<div id="image-manager">
Implement including files in tpl
{*php}
			Javascript::addFile(Request::$url['base'] . DIR_LIBS . "mediamanager/MediaManager.js");
			echo "<a href=\"#\" onclick=\"selectMedia(null, 'image');return false;"
				. "\"><img src=\"".Environment::$smarty->getTemplateVars('iconsPath')."32/image.png\" alt=\"I\" title=\"".tg('Image manager')."\" /></a>";
			echo "<a href=\"#\" onclick=\"selectMedia(null, 'file');return false;"
				. "\"><img src=\"".Environment::$smarty->getTemplateVars('iconsPath')."32/filetype_doc.png\" alt=\"F\" title=\"".tg('File manager')."\" /></a>";
{/php*}
				    </div><!-- image-manager -->
{/if}

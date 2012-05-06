{if $actualUserInfo and $actualUserInfo->id}
	<div id="image-manager">
	<a href="#" onclick="selectMedia(null, 'image');return false;"><img src="{$iconsPath}32/image.png" alt="I" title="{tg}Image manager{/tg}" /></a>
	<a href="#" onclick="selectMedia(null, 'file');return false;"><img src="{$iconsPath}32/filetype_doc.png" alt="F" title="{tg}File manager{/tg}" /></a>
	</div><!-- image-manager -->
{/if}

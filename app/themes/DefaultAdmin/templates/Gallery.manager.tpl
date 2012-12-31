{include file='Base.part.pageHeader.tpl' }

<div class="manager">

<form name="imageform" action="" method="post"><fieldset>

<script type="text/javascript">
/**
 * Jumploader file status changed notification
 */
function uploaderStatusChanged( uploader) {ldelim}
	if (uploader.getStatus() == uploader.STATUS_READY) {ldelim}
		//refresh();
		window.location.reload( false );
	{rdelim}
{rdelim}	
</script>

<h1>{$title}</h1>

<div class="toolbar">
<a href="#" onclick="window.location.reload()" title="{tg}Refresh{/tg}"><img src="{$iconsPath}32/reload.png" alt="refresh" /></a>
<a href="{linkto package=Gallery controller=Images action=actionImageManagerNewFile dir=$actualDir type=$actualType}" title="{tg}Upload local file{/tg}"><img src="{$iconsPath}32/image_up.png" alt="uload" /></a>
<a href="{linkto package=Gallery controller=Images action=actionImageManagerNewDir dir=$actualDir type=$actualType}" title="{tg}New directory{/tg}"><img src="{$iconsPath}32/folder_add.png" alt="new dir" /></a>
<a href="#" onclick="{$advanceUploadAppletWindowOpenJS}"><img src="{$iconsPath}32/image_multi_add.png" alt="new dir" /></a>
{*$advanceUploadAppletScript*}
<input type="text" value="" name="f_size" id="f_size" />
</div>

<div class="items" id="mediaManagerItems">
{foreach from=$dirItems item=item}
<div class="item {$item->class}">
<a href="{$item->link}" title="{$item->desc}" class="{$item->class}"{if $item->onclick} onclick="{$item->onclick}"{/if}{if $item->ondoubleclick} ondblclick="{$item->ondoubleclick}"{/if}>
<span class="image"><img src="{$item->image}" alt="" /></span>
<span class="title">{$item->title}</span>
</a>
{if $item->delete}<a href="{$item->delete}" class="deleteItem" onclick="return confirm('{tg}Are you sure to delete this item?{/tg}');"><img src="{$iconsPath}16/remove.png" alt="x" /></a>{/if}
{if $item->edit}<a href="{$item->edit}" class="editItem"><img src="{$iconsPath}16/edit.png" alt="e" /></a>{/if}
</div>
{/foreach}
<div class="clear"></div>
</div>

<div class="buttons"> 
	  {*<button type="button" class="button updateButton" onclick="return refresh();">{tg}Reload{/tg}</button>*}
	  <button type="button" class="button" id="insert" onclick="return onOK();">{tg}OK{/tg}</button>
	  <button type="button" class="button" id="cancel" onclick="return onCancel();">{tg}Cancel{/tg}</button>
	  <div class="clear"></div>
</div>

<div class="statusbar">
<input type="text" value="" name="f_url" id="f_url" />
</div>

</fieldset></form>

</div>

<script type="text/javascript">
init();
</script>

{include file='Base.part.pageFooter.tpl' }


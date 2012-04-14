{if $actualUserInfo and $itemItem->id}
{if $itemActionSimple}
<div class="admin_edit_popup">
<a href="#" onclick="return windowPopupAjax('{linkto package=$itemPackage controller=$itemController action=$itemActionSimple}', null, null, null, {ldelim}'width': '800'{rdelim});" title="{tg}Add new item in a popup window{/tg}">
<img src="{$iconsPath}32/add.png" alt="{tg}New in popup{/tg}" />
</a>
</div>
{elseif $itemAction}
<div class="admin_edit">
<a href="{linkto package=$itemPackage controller=$itemController action=$itemAction}" title="{tg}Add a new item{/tg}">
<img src="{$iconsPath}32/add.png" alt="{tg}New{/tg}" />
</a>
</div>
{/if}
{/if}

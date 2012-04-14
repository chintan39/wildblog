{if $actualUserInfo and $itemItem->id}
{if $itemActionSimple}
<div class="admin_edit_popup">
<a href="#" onclick="return windowPopupAjax('{linkto package=$itemPackage controller=$itemController action=$itemActionSimple dataItem=$itemItem}', null, null, null, {ldelim}'width': '800'{rdelim});" title="{tg}Edit this item in a popup window{/tg}">
<img src="{$iconsPath}32/edit.png" alt="{tg}Edit in popup{/tg}" />
</a>
</div>
{elseif $itemAction}
<div class="admin_edit">
<a href="{linkto package=$itemPackage controller=$itemController action=$itemAction dataItem=$itemItem}" title="{tg}Edit this item{/tg}">
<img src="{$iconsPath}32/edit.png" alt="{tg}Edit{/tg}" />
</a>
</div>
{/if}
{/if}

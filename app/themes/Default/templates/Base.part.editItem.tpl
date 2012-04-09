{if $actualUserInfo and $itemItem->id}
{if $itemAction}
<div class="admin_edit">
<a href="{linkto package=$itemPackage controller=$itemController action=$itemAction dataItem=$itemItem}" title="{tg}Edit this item{/tg}">
<img src="{$iconsPath}32/edit.png" alt="{tg}Edit{/tg}" />
</a>
</div>
{/if}
{if $itemActionSimple}
<div class="admin_edit_popup">
<a href="#" onclick="return windowPopupAjaxGetContent('{linkto package=$itemPackage controller=$itemController action=$itemActionSimple dataItem=$itemItem}');" title="{tg}Edit this item in a popup window{/tg}">
<img src="{$iconsPath}32/edit.png" alt="{tg}Edit in popup{/tg}" />
</a>
</div>
{/if}
{/if}

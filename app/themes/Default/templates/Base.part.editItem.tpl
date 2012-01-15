{if $actualUserInfo and $itemItem->id}
<div class="admin_edit">
<a href="{linkto package=$itemPackage controller=$itemController action=$itemAction dataItem=$itemItem}" title="{tg}Edit this item{/tg}">
<img src="{$iconsPath}32/edit.png" alt="{tg}Edit{/tg}" />
</a>
</div>
{/if}

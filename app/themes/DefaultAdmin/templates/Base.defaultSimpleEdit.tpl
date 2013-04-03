<!-- ##title={$title|default:'Edit':tg}## -->
<!-- ##size=700x500## -->
{include file='Base.part.pageHeader.tpl'  ajax=1}
<!-- protectedForm:{$form.identifier} -->
{include file='Base.part.cleanForm.tpl'  ajax=1}
{if $detailLink}<a href="{$detailLink}" class="detailItem" title="{tg}View item on front-page{/tg}"></a>{/if}
{if $viewLink}<a href="{$viewLink}" class="viewItem" title="{tg}View item detail{/tg}"></a>{/if}
{if $removeLink}<a href="{$removeLink}" class="removeItem" title="{tg}Remove item{/tg}" onclick="return confirm('{tg}You are going to remove the whole item. Are you sure?{/tg}')"></a>{/if}
{if $removeLinkSimple}<a href="{$removeLinkSimple}" class="removeItem" title="{tg}Remove item{/tg}" onclick="return confirm('{tg}You are going to remove the whole item. Are you sure?{/tg}')"></a>{/if}
{include file='Base.part.pageFooter.tpl'  ajax=1}


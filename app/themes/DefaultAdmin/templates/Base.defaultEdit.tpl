{include file='Base.part.header.tpl'}
<h1>{$title|default:'Edit':tg}</h1>
{include file='Base.part.cleanForm.tpl'  ajax=1}
{if $detailLink and not $requestIsAjax}<a href="{$detailLink}" class="detailItem" title="{tg}View item on front-page{/tg}"></a>{/if}
{if $viewLink and not $requestIsAjax}<a href="{$viewLink}" class="viewItem" title="{tg}View item detail{/tg}"></a>{/if}
{if $removeLink and not $requestIsAjax}<a href="{$removeLink}" class="removeItem" title="{tg}Remove item{/tg}" onclick="return confirm('{tg}Are you sure?{/tg}')"></a>{/if}
{if $editLink and not $requestIsAjax}<a href="{$editLink}" class="editItem" title="{tg}Edit item{/tg}"></a>{/if}
{include file='Base.part.footer.tpl'}


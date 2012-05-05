{include file='part.header'}
<h1>{$title|default:'Edit':tg}</h1>
{include file='part.cleanForm' theme=Common ajax=1}
{if $detailLink and not $requestIsAjax}<a href="{$detailLink}" class="detailItem" title="{tg}View item detail{/tg}"></a>{/if}
{if $removeLink and not $requestIsAjax}<a href="{$removeLink}" class="removeItem" title="{tg}Remove item{/tg}" onclick="return confirm('{tg}Are you sure?{/tg}')"></a>{/if}
{include file='part.footer'}


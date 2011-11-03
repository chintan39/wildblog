{require file='part.header'}
<h1>{$title|default:'Edit':tg}</h1>
{require file='part.cleanForm' theme=Common}
{if $detailLink}<a href="{$detailLink}" class="detailItem" title="{tg}View item detail{/tg}"></a>{/if}
{if $removeLink}<a href="{$removeLink}" class="removeItem" title="{tg}Remove item{/tg}" onclick="return confirm('{tg}Are you sure?{/tg}')"></a>{/if}
{require file='part.footer'}


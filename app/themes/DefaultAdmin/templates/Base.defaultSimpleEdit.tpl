{require file='part.header' theme=Common}
<h1>{$title|default:'Edit':tg}</h1>
{require file='part.cleanForm' theme=Common ajax=1}
{if $detailLink}<a href="{$detailLink}" class="detailItem" title="{tg}View item detail{/tg}"></a>{/if}
{require file='part.footer' theme=Common}


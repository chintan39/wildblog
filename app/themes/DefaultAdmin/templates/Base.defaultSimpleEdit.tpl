<!-- ##title={$title|default:'Edit':tg}## -->
<!-- ##size=700x500## -->
{require file='part.header' theme=Common ajax=1}
{require file='part.cleanForm' theme=Common ajax=1}
{if $detailLink}<a href="{$detailLink}" class="detailItem" title="{tg}View item detail{/tg}"></a>{/if}
{if $removeLink}<a href="{$removeLink}" class="removeItem" title="{tg}Remove item{/tg}" onclick="return confirm('{tg}Are you sure?{/tg}')"></a>{/if}
{if $removeLinkSimple}<a href="{$removeLinkSimple}" class="removeItem" title="{tg}Remove item{/tg}" onclick="return confirm('{tg}Are you sure?{/tg}')"></a>{/if}
{require file='part.footer' theme=Common ajax=1}


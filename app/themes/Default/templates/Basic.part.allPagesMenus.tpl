{strip}
{if $allPagesMenus.$menuName and $allPagesMenus.$menuName.items}
{require file='part.itemLinkTree' items=$allPagesMenus.$menuName.items deep=3}
{/if}
{/strip}



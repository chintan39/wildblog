{strip}
{if $allPagesMenus and $allPagesMenus.$menuName and $allPagesMenus.$menuName->data and $allPagesMenus.$menuName->data.items}
{require file='part.itemLinkTree' items=$allPagesMenus.$menuName->data.items deep=3}
{/if}
{/strip}



{strip}
{if $allPagesMenus and $allPagesMenus.$menuName and $allPagesMenus.$menuName->links}
{require file='part.linkTree' items=$allPagesMenus.$menuName->links deep=3}
{/if}
{/strip}



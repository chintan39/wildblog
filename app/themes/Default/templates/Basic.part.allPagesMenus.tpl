{strip}
{if $allPagesMenus and $allPagesMenus.$menuName and $allPagesMenus.$menuName->links}
{include file='Base.part.linkTree.tpl' items=$allPagesMenus.$menuName->links deep=3}
{/if}
{/strip}



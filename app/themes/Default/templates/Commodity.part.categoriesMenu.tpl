{if $categoriesTree->data.items}
<h2>{tg}Categories list header{/tg}</h2>
{include file='Base.part.itemLinkTree.tpl' items=$categoriesTree->data.items deep=3 ulclass=sidemenu}
{/if}


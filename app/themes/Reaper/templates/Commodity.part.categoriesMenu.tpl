{if $categoriesTree->data.items}
<h2>{tg}Categories list header{/tg}</h2>
{require file='part.itemLinkTree' items=$categoriesTree->data.items deep=3 ulclass=sidemenu}
{/if}


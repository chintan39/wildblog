{if $articlesTree->data.items}
<h2>{tp}Articles tree menu header{/tp}</h2>
{include file='part.itemLinkTree' items=$articlesTree->data.items deep=3 ulclass=sidemenu}
{/if}


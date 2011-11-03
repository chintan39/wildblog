{if $articlesTags}
<h2>{tg}Articles{/tg}</h2>
{foreach from=$articlesTags item=tagArticles key=keyArt}
{$keyArt}:<br />
{if $tagArticles.articles}
{foreach from=$tagArticles.articles item=itemArticle}
<a href="{$itemArticle->link}">{$itemArticle->title}</a>
{/foreach}
{/if}
{/foreach}
{if $articlesTags.leftpanel.articles}
{foreach from=$articlesTags.leftpanel.articles item=itemArticle}
<a href="{$itemArticle->link}">{$itemArticle->title}</a>
{/foreach}
{/if}
{/if}


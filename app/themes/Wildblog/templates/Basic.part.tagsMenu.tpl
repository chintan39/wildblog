{if $articlesTags}
<div class="box">
<div class="dark headline">{tg}Articles{/tg}</div><!-- dark -->
<div class="light">
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
</div><!-- light -->
</div><!-- box -->
{/if}


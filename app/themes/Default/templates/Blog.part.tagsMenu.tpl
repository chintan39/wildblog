{if $tagsMenu->data.items}
<h2>{tg}tags{/tg}</h2>
{foreach from=$tagsMenu->data.items item=tag}
{math assign=size equation="round(tagPostsCount * 200 / postsAll) + minumum" tagPostsCount=$tag->postsCount postsAll=70 minumum=70}
<a href="{$tag->link}" style="font-size: {$size}%">{$tag->title}</a>
{/foreach}
{/if}


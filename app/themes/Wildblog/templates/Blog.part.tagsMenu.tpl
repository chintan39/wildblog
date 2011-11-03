{if $tagsMenu->data.items}
<div class="box">
<div class="dark headline">{tg}tags{/tg}</div><!-- dark -->
<div class="light">
{foreach from=$tagsMenu->data.items item=tag}
{math assign=size equation="round(tagPostsCount * 200 / postsAll) + minumum" tagPostsCount=$tag->postsCount postsAll=70 minumum=70}
<a href="{$tag->link}" style="font-size: {$size}%">{$tag->title}</a>
{/foreach}
</div><!-- light -->
</div><!-- box -->
{/if}


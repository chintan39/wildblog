{require file='part.header'}

{if $title and not $notitle}<h1>{$title}</h1>{/if}

{if $blogposts->data.items}
{foreach from=$blogposts->data.items item=post}
	<div class="article">
	<h1><a href="{$post->link}">{$post->title}</a></h1>
	<div class="date">Date:&nbsp;{$post->published|date_format:"%m/%e"}</div>
	{$post->text}
	<div class="clear"></div>
	{require file='part.tags' tags=$post->tags package=Blog}
	<div class="comments">
	<a href="{$post->link}#comment_add">add comment</a>
	<a href="{$post->link}#comments">view posted comments ({$post->commentsCount})</a>
	</div>
	</div>
{/foreach}
{else}
	<p>{tg}No posts found.{/tg}</p>
{/if}

{generate_paging collection=$blogposts}

{require package=Base file='part.addNewItem' itemPackage=Blog itemController=Posts itemAction=actionNew}

{require file='part.footer'}


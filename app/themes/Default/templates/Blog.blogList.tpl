{include file='Base.part.header.tpl'}

{*generate_paging collection=$blogposts showOnlyKeys=prev*}

{if $title and not $notitle and not $requestIsAjax}<h1>{$title}</h1>{/if}

<div id="{$blogposts->containerId}">

{if $blogposts->data.items}
{foreach from=$blogposts->data.items item=post}
	<div class="article">
	<h1><a href="{$post->link}">{$post->title}</a></h1>
	<div class="date">Date:&nbsp;{$post->published|date_format:"%m/%e"}</div>
	{$post->text}
	<div class="clear"></div>
	{include file='Blog.part.tags.tpl' tags=$post->tags package=Blog}
	<div class="comments">
	<a href="{$post->link}#comment_add">add comment</a>
	<a href="{$post->link}#comments">view posted comments ({$post->commentsCount})</a>
	</div>
	{include  file='Basic.part.editItem.tpl' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}
	</div>
{/foreach}
{else}
	<p>{tg}No posts found.{/tg}</p>
{/if}

{generate_paging collection=$blogposts showOnlyKeys=next}

</div>

{include  file='Base.part.addNewItem.tpl' itemPackage=Blog itemController=Posts itemAction=actionNew itemActionSimple=actionSimpleNew}

{include file='Base.part.footer.tpl'}


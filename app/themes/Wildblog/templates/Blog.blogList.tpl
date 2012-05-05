{include file='Base.part.header'}

{*generate_paging collection=$blogposts showOnlyKeys=prev*}

{if $title and not $notitle and not $requestIsAjax}<h1>{$title}</h1>{/if}

<div id="{$blogposts->containerId}">

{if $blogposts->data.items}
{foreach from=$blogposts->data.items item=post}
	<div class="article">
	<h1><a href="{$post->link}">{$post->title}</a></h1>
	<div class="date"><span class="w">{$post->published|date_format:"%m"|month_format:"%nam"}|{$post->published|date_format:"%e"}</span> <span class="y">{$post->published|date_format:"%Y"}</span></div>
	{$post->text}
	<div class="clear"></div>
	{include file='Blog.part.tags' tags=$post->tags package=Blog}
	<div class="comments">
	<a href="{$post->link}#comment_add"><img src="{$iconsPath}16/comment_add.png" alt="" title="" /> add comment</a>
	<a href="{$post->link}#comments"><img src="{$iconsPath}16/comments.png" alt="" title="" /> view posted comments ({$post->commentsCount})</a>
	</div>
	{include  file='Basic.part.editItem' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}
	</div>
	<br /><br /><br />
{/foreach}
{else}
	<p>{tg}No posts found.{/tg}</p>
{/if}

{generate_paging collection=$blogposts showOnlyKeys=next}

</div>

{include  file='Base.part.addNewItem' itemPackage=Blog itemController=Posts itemAction=actionNew itemActionSimple=actionSimpleNew}

{include file='Base.part.footer'}


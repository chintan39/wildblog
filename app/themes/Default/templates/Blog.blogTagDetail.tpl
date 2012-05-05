{include file='Base.part.header'}

{foreach from=$tag->posts->data.items item=postItem}
	<div class="article">
	<h1><a href="{$postItem->link}" rel="external">{$postItem->title}</a></h1>
	<div class="date">{$postItem->published|date_format:"%m/%e"}</div>
	{$postItem->text}
	{include file='Blog.part.tags' tags=$postItem->tags package=Blog}
	<div class="comments">
	<a href="{$postItem->link}#comment_add"><img src="images/ico/16/comment_add.png" alt="" title="" /> {tg}add comment{/tg}</a>
	<a href="{$postItem->link}#comments"><img src="images/ico/16/comments.png" alt="" title="" /> {tg}view posted comments{/tg} ({$postItem->commentsCount})</a>
	</div>
	</div>
	<br /><br /><br />
{/foreach}

{generate_paging collection=$tag}

{include file='Base.part.footer'}


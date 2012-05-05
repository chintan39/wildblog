{strip}
<div class="comments">
<a name="comments"></a>
{if $comments}
<h3>{tg}Posted comments{/tg}</h3> 
{foreach from=$comments item=comment name=comment}
	{if $comment->title}<h4>{$comment->title}</h4>{/if}
	{if $comment->author_name}<p><i>{tg}Author{/tg}: {$comment->author_name}{if $comment->author_web} (<a href="{$comment->author_web}" rel="external">{$comment->author_web}</a>){/if}</i></p>{/if}
	{$comment->text}
{/foreach}
{/if}
<a name="comment_add"></a>
{include file='part.cleanForm' theme=Common formId=comment formClass="no-border"}
</div>
{/strip}



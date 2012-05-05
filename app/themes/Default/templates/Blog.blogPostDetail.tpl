{include file='part.header'}

	<div class="article">
	<h1>{$post->title}</h1>
	<div class="date">{$post->published|date_format:"%m/%e"}</div>
	{$post->text|addlinks}
	<div class="clear"></div>
	{include package=Blog file='part.tags' tags=$post->tags}
    {include package=Blog file='part.relatedPostsDown'}
	{include package=Blog file='part.comments' form=$commentNewForm comments=$post->comments}
	{include package=Base file='part.editItem' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}
	</div>

{include file='part.footer'}


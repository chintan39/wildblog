{require file='part.header'}

	<div class="article">
	<h1>{$post->title}</h1>
	<div class="date">{$post->published|date_format:"%m/%e"}</div>
	{$post->text|addlinks}
	<div class="clear"></div>
	{require package=Blog file='part.tags' tags=$post->tags}
    {require package=Blog file='part.relatedPostsDown'}
	{require package=Blog file='part.comments' form=$commentNewForm comments=$post->comments}
	{require package=Base file='part.editItem' itemPackage=Blog itemController=Posts itemAction=actionEdit itemItem=$post}
	</div>

{require file='part.footer'}


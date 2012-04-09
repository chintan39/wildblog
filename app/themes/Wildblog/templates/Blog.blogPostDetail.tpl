{require file='part.header'}

	<div class="article">
	<h1>{$post->title}</h1>
	<div class="date"><span class="w">{$post->published|date_format:"%m"|month_format:"%nam"}|{$post->published|date_format:"%e"}</span> <span class="y">{$post->published|date_format:"%Y"}</span></div>
	{$post->text|addlinks}
	<div class="clear"></div>
	{require package=Blog file='part.tags' tags=$post->tags}
    {require package=Blog file='part.relatedPostsDown'}
	{require package=Blog file='part.comments' form=$commentNewForm comments=$post->comments}
	{require package=Base file='part.editItem' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}
	</div>

{require file='part.footer'}


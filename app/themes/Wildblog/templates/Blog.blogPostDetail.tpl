{include file='Base.part.header.tpl'}

	<div class="article">
	<h1>{$post->title}</h1>
	<div class="date"><span class="w">{$post->published|date_format:"%m"|month_format:"%nam"}|{$post->published|date_format:"%e"}</span> <span class="y">{$post->published|date_format:"%Y"}</span></div>
	{$post->text|addlinks}
	<div class="clear"></div>
	{include package=Blog file='Blog.part.tags.tpl' tags=$post->tags}
    {include package=Blog file='Blog.part.relatedPostsDown.tpl'}
	{include package=Blog file='Blog.part.comments.tpl' form=$commentNewForm comments=$post->comments}
	{include  file='Basic.part.editItem.tpl' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}
	</div>

{include file='Base.part.footer.tpl'}


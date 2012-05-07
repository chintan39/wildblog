{include file='Base.part.header.tpl'}

<h1>{$post->title}</h1>
{$post->text|addlinks}
<div class="clear"></div>
{include package=Blog file='Blog.part.tags.tpl' tags=$post->tags}
{include package=Blog file='Blog.part.relatedPostsDown.tpl'}
{include package=Blog file='Blog.part.comments.tpl' form=$commentNewForm comments=$post->comments}
{include  file='Base.part.editItem.tpl' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}

{include file='Base.part.footer.tpl'}


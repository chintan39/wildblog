{include file='Base.part.header'}

<h1>{$post->title}</h1>
{$post->text|addlinks}
<div class="clear"></div>
{include package=Blog file='Blog.part.tags' tags=$post->tags}
{include package=Blog file='Blog.part.relatedPostsDown'}
{include package=Blog file='Blog.part.comments' form=$commentNewForm comments=$post->comments}
{include  file='Base.part.editItem' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}

{include file='Base.part.footer'}


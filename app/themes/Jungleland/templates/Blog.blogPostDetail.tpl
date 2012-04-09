{require file='part.header'}

<h1>{$post->title}</h1>
{$post->text|addlinks}
<div class="clear"></div>
{require package=Blog file='part.tags' tags=$post->tags}
{require package=Blog file='part.relatedPostsDown'}
{require package=Blog file='part.comments' form=$commentNewForm comments=$post->comments}
{require package=Base file='part.editItem' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}

{require file='part.footer'}


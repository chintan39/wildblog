{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
            <div class="page-2-col-2 right-40">
                <h2 class="top-2">{$post->title}</h2>
                <p><strong>{$post->published|date_format:"%e. %B %Y"}</strong></p>
                {$post->text|addlinks}
                {include  file='Base.part.editItem.tpl' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}
	{include package=Blog file='Blog.part.tags.tpl' tags=$post->tags}
                </div>
        </div>
        <div class="clear"></div>
      </div>
  </section> 

{include file='Base.part.footer.tpl'}


{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
            <div class="page-2-col-2 right-40">
                <h2 class="top-2">{$news->title}</h2>
                <p><strong>{$news->published|date_format:"%e. %B %Y"}</strong></p>
                {$news->text|addlinks}
                {include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=News itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$news}
                </div>
        </div>
        {if strpos($news->text, '__wide_content__') === false}
        <div class="grid_5">
        	{include file='Basic.part.recentNews.tpl'}
        	
        	{include file='Gallery.part.galleriesList.tpl'}
        </div>     
        {/if}
        <div class="clear"></div>
      </div>
  </section> 

{include file='Base.part.footer.tpl'}


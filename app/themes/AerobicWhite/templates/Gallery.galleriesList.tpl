{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
            <div class="page-2-col-2 right-40">
                <h2 class="top-2">{$title}</h2>
				{if $galleriesList->data.items}
				{foreach from=$galleriesList->data.items item=gallery}
				<h3><a href="{$gallery->link}">{$gallery->title}</a></h3>
				<p><a href="{$gallery->link}">{if $gallery->titleimage}<img src="{$gallery->titleimage|thumbnail:150:150:'c'}" />{/if}</a>
				{$gallery->description|strip_tags|truncate}</p>  
				<div class="clear"></div>
				{/foreach}
				{else}
					<p>{tg}No galleries found.{/tg}</p>
				{/if}
				
				{include  file='Base.part.addNewItem.tpl' itemPackage=Gallery itemController=Galleries itemAction=actionNew itemActionSimple=actionSimpleNew}
				
				{generate_paging collection=$galleriesList}
                </div>
        </div>
        <div class="grid_5">
        
        	{include file='Basic.part.recentNews.tpl'}
        	
        	{include file='Gallery.part.galleriesList.tpl'}
        </div>     
        <div class="clear"></div>
      </div>
  </section> 

{include file='Base.part.footer.tpl'}


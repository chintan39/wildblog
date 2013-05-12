{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
            <div class="page-2-col-2 right-40">
                <h2 class="top-2">{$title}</h2>
				{if $gallery->images->data.items}
				{foreach from=$gallery->images->data.items item=image}
				<a href="{$image->image}" rel="lightbox[images]" title="{$image->description|default:$image->title|strip_tags|truncate}">{if $image->image}<img src="{$image->image|thumbnail:150:100:'c'}" title="{$image->description|default:$image->title|strip_tags|truncate}" alt="{$image->title}" />{/if}</a>
				{/foreach}
				<div class="clear"></div>
				{else}
					<p>{tg}No images found.{/tg}</p>
				{/if}
				
				{generate_paging collection=$gallery->images}
				
				{include  file='Base.part.editItem.tpl' itemPackage=Gallery itemController=Galleries itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$gallery}
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


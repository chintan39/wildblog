{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
			<h2 class="top-1 cursive">{$title}</h2>
			{if $galleriesList->data.items}
			{foreach from=$galleriesList->data.items item=gallery key=key}
			<div class="wrapper box-2">
				<div class="number"><strong>{$key+1}</strong></div>
				<div class="extra-wrap border-1">
					<a href="{$gallery->link}" class="color-5">{$gallery->title}</a>
					{if $gallery->titleimage}<a href="{$gallery->link}"><img src="{$gallery->titleimage|thumbnail:150:150:'c'}" style="float: right;" /></a>{/if}
					<p class="line_height_18">{$gallery->description|strip_tags|truncate}</p>
				</div>
			</div>
			<div class="clear"></div>
			{/foreach}
			{/if}
			{include  file='Base.part.addNewItem.tpl' itemPackage=Gallery itemController=Galleries itemAction=actionNew itemActionSimple=actionSimpleNew}
			
			{generate_paging collection=$galleriesList}
        </div>
        <div class="grid_5">
        
        	{include file='Basic.part.recentNews.tpl'}
        	
        	{include file='Gallery.part.galleriesList.tpl'}
        </div>     
        <div class="clear"></div>
      </div>
  </section> 

{include file='Base.part.footer.tpl'}


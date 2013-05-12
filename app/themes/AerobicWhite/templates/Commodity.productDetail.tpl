{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
            <div class="page-2-col-2 right-40">
                <h2 class="top-2">{$product->title}</h2>
				{if $product->image}
				<a href="{$product->image}" class="float-left" rel="lightbox">
					<img src="{$product->image|thumbnail:300:400:'r'}" alt="#" title="{$product->title}" />
				</a>
				{/if}
				
				{$product->text}
				<p class="price">{tg}Price including WAT{/tg}: <span class="price">{$product->price|price}&nbsp;Kč</span></p>
				
				<div class="clear"></div>
                {include  file='Base.part.editItem.tpl' itemPackage=Commodity itemController=Products itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$product}
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


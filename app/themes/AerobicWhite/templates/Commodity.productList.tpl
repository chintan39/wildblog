{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
			<h2 class="top-1 cursive">{tg}Products{/tg}</h2>
			{if $products->data.items}
			{foreach from=$products->data.items item=product key=key}
			<div class="wrapper box-2">
				<div class="number"><strong>{$key+1}</strong></div>
				<div class="extra-wrap border-1">
					<a href="{$product->link}" class="color-5">{$product->title}</a>
					{if $product->image}<a href="{$product->link}"><img src="{$product->image|thumbnail:150:150:'r'}" style="float: right; margin: 20px 50px" /></a>{/if}
					<p class="line_height_18">{$product->text|strip_tags|truncate}</p>
					<p class="price">{tg}Price including WAT{/tg}: <span class="price">{$product->price|price}&nbsp;Kč</span></p>
				</div>
			</div>
			<div class="clear"></div>
			{/foreach}
			{/if}

			{generate_paging collection=$products}
        </div>
        <div class="grid_5">
        
        	{include file='Basic.part.recentNews.tpl'}
        	
        	{include file='Gallery.part.galleriesList.tpl'}
			<!-- LinkBuilding list-->
        	{include file='LinkBuilding.part.partnerLinks.tpl'}
			<!-- LinkBuilding end -->
        </div>     
        <div class="clear"></div>
      </div>
  </section> 

{include file='Base.part.footer.tpl'}



{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
			<h2 class="top-1 cursive">{tg}Blog{/tg}</h2>
			{if $blogposts->data.items}
			{foreach from=$blogposts->data.items item=item key=key}
			<div class="wrapper box-2">
				<div class="number"><strong>{$key+1}</strong></div>
				<div class="extra-wrap border-1">
					<a href="{$item->link}" class="color-5">{$item->title}</a>
					<p class="line_height_18">{$item->published|date_format:"%e. %B %Y"}<br />{$item->text|strip_tags|truncate:200}</p>
				</div>
			</div>
			{/foreach}
			{/if}

			{generate_paging collection=$blogposts}

		        {include  file='Base.part.editItem.tpl' itemPackage=Blog itemController=Posts itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$post}
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


{include file='Base.part.header.tpl'}

  <section id="content" class="content">
      <div class="container_16">
      	<div class="grid_11">
            <div class="page-2-col-2 right-40">
                <h2 class="top-2">{tg}News{/tg}</h2>
				{if $news->data.items}
				{foreach from=$news->data.items item=item}
					<div class="news">
					<h3><a href="{$item->link}">{$item->title}</a></h3>
					<div class="date">{$item->published|date_format2:"%relative"}</div>
					{$item->preview}
					<div class="clear"></div>
					</div>
				{/foreach}
				{else}
					<p>{tg}No news found.{/tg}</p>
				{/if}
				{generate_paging collection=$news}
				
				{include  file='Base.part.addNewItem.tpl' itemPackage=Basic itemController=News itemAction=actionNew itemActionSimple=actionSimpleNew}
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


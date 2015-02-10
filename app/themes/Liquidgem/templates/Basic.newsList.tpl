{include file='Base.part.header.tpl'}

{include file='Basic.part.headerimage.tpl'}

    <header><!-- Work Showcase Section Start -->
    
    	{if $title and not $notitle}<h1>{$title}</h1>{/if}
    </header>
    

    <section id="workbody"><!-- Project images start -->
{if $news->data.items}
{foreach from=$news->data.items item=item}
	<div class="news">
	<h3><a href="{$item->link}">{$item->title}</a></h3>
	<div class="date">{$item->published|date_format:"%e. %B %Y"}</div>
	{$item->preview}
	<p>&nbsp;</p>
	<div class="clear"></div>
	</div>
{/foreach}
{else}
	<p>{tg}No news found.{/tg}</p>
{/if}

{generate_paging collection=$news}

{include  file='Base.part.addNewItem.tpl' itemPackage=Basic itemController=News itemAction=actionNew itemActionSimple=actionSimpleNew}

    </section><!-- Project images end -->
    


    <hr/>	<!-- Horizontal Line -->


{include file='Base.part.footer.tpl'}


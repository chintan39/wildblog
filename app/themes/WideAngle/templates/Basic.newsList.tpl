{include file='Base.part.header.tpl'}


		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div id="main" class="container">
					
					<div class="row">
					
						<!-- Content -->
							<div id="content" class="8u">
								<article>
									<header>
{if $title and not $notitle}<h2>{$title}</h2>{/if}
									</header>

{if $news->data.items}
{foreach from=$news->data.items item=item}
	<div class="news">
	<h2><a href="{$item->link}">{$item->title}</a></h2>
	<div class="date">{tg}Published:{/tg} {$item->published|date_format2:"%e. %mnamelong"}{*$item->published|date_format2:"%relative"*}</div>
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
								</article>
							</div>
						
						<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section class="section-padding">
        	{include file='Basic.part.shortContact.tpl'}
								</section>
								
							</div>
						
					</div>
					
				</div>
			</div>


{include file='Base.part.footer.tpl'}


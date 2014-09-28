{include file='Base.part.header.tpl'}

		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div id="main" class="container">
					
					<div class="row">
					
						<!-- Content -->
							<div id="content" class="8u">
								<article>
									<header>
										<h2>{$title}</h2>
									</header>

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

								</article>
							</div>
						
						<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section class="section-padding">
        	{include file='Basic.part.recentNews.tpl'}
								</section>
	
								
							</div>
						
					</div>
					
				</div>
			</div>





{include file='Base.part.footer.tpl'}


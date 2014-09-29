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

{if $gallery->images->data.items}
{foreach from=$gallery->images->data.items item=image}
<a href="{$image->image}" rel="lightbox[images]" data-lightbox="images" title="{$image->description|default:$image->title|strip_tags|truncate}">{if $image->image}<img src="{$image->image|thumbnail:150:100:'c'}" title="{$image->description|default:$image->title|strip_tags|truncate}" alt="{$image->title}" />{/if}</a>
{/foreach}
<div class="clear"></div>
{else}
	<p>{tg}No images found.{/tg}</p>
{/if}

{generate_paging collection=$gallery->images}

{include  file='Base.part.editItem.tpl' itemPackage=Gallery itemController=Galleries itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$gallery}


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


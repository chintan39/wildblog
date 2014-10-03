{include file='Base.part.header.tpl'}


		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div id="main" class="container">
					
					<div class="row">
					
						<!-- Content -->
							<div id="content" class="8u">
								<article>
									<header>
<h2>{$news->title}</h2>
									</header>

	<div class="date">{tg}Published:{/tg} {$news->published|date_format2:"%e. %mnamelong"}</div>
	{$news->text|addlinks}
	<div class="clear"></div>
	{include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=News itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$news}
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


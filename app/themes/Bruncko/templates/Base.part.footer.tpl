					</div>
					<div class="col-1">
						{if $useReferences}
						{include package=References file='References.part.references.tpl'}
						{else}
						{include package=Gallery file='Gallery.part.galleriesList.tpl'}
						{/if}
						<hr class="invisible" />
						{if $isHomepage}
						<div class="contact">
							{include file='Basic.part.shortContact.tpl' package=Basic}
						</div>
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
		<div id="header">
			<hr class="invisible" />
			<div class="logo"><a href="{$base}"></a></div>
			<div class="site-nav">
				<ul>
					<li><a href="{$base}">{tp}Home{/tp}</a></li>
					<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='2'}">{tp}History{/tp}</a></li>
					<li><a href="{linkto package=Gallery controller=Galleries action=actionGalleriesList}">{tp}Demonstration of work{/tp}</a></li>
					<li><a href="{linkto package=References controller=References action=actionReferencesList}">{tp}References top{/tp}</a></li>
					<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='3'}">{tp}Pricelist{/tp}</a></li>
					<li><a href="{linkto package=Basic controller=Articles action=actionDetail dataItem='4'}">{tp}Contact{/tp}</a></li>
				</ul>
			</div>
		</div>
</div>

<!-- FOOTER -->

<div id="footer">
	<hr class="invisible" />
	<div class="indent">
		<div class="fleft">
		
{include file='Base.part.wwFooter.tpl' sep=' '}

		| <a href="{linkto controller=Sitemap action=actionSitemap}">Mapa webu</a>
		</div>
		<div class="fright">Designed by: <a href="http://www.templates.com">Website Templates</a> 
		</div>
	</div>
</div>

{include file='Basic.part.htmlAreas.tpl' package=Basic}

{include file='Base.part.adminBox.tpl' }

{include file='Base.part.footer.tpl' }

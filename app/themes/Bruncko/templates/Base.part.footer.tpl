					</div>
					<div class="col-1">
						{if $useReferences}
						{require package=References file='part.references'}
						{else}
						{require package=Gallery file='part.galleriesList'}
						{/if}
						<hr class="invisible" />
						{if $isHomepage}
						<div class="contact">
							{require file='part.shortContact' package=Basic}
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
		<div class="fleft">&copy; Jozef Bruncko {$now|date_format:"%Y"}, Powered by <a href="http://code.google.com/p/wildblog/" title="wild-blog">wildblog project</a>
		| <a href="http://www.wild-web.eu" title="Wild-web">Jan Horák</a>
		| <a href="{linkto controller=Sitemap action=actionSitemap}">Mapa webu</a>
		| <a href="{$base}admin/">Admin</a>
		</div>
		<div class="fright">Designed by: <a href="http://www.templates.com">Website Templates</a> 
		</div>
	</div>
</div>

{require file='part.htmlAreas' package=Basic}

{require file='part.adminBox' theme=Common}

{require file='part.footer' theme=Common}

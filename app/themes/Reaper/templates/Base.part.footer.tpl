		{tp}All page note.{/tp}
		</div>	
			
		<div id="rightbar">
			
			{include file='Commodity.part.references.tpl'}
			{include file='Commodity.part.actions.tpl'}
			{include file='Commodity.part.favourites.tpl'}
			{include file='Basic.part.advertisements.tpl' package=Basic}
			{include file='Basic.part.htmlAreas.tpl' package=Basic}
			
			<div class="pause"></div>
		</div>
		
		</div><!-- mainleft -->
		
		<div id="sidebar" >							
				
			{include file='Basicpart.articlesMenu.tpl'}
			{include file='Commodity.part.categoriesMenu.tpl'}
			{include file='Commodity.part.manofacturersList.tpl'}
			
		</div><!-- sidebar -->
			
		<div id="headerphoto">
		<div id="name">{tp}header page name{/tp}</div>
		<div id="address">{tp}header page street<br />header page city<br />header page phone<br />header page email{/tp}</div>	
      {include file='Base.part.navigation.tpl'}
		</div>
		
	<!-- content-wrap ends here -->		
	</div>

	<div id="header">				
			
		<h2 id="logo"><a href="">{tp}header top title{/tp}</a></h2>	
		<h2 id="slogan">{tp}header top subtitle{/tp}</h2> 
		
		{include file='Base.part.searchForm.tpl' searchFormClass="searchform" searchFormSubmit="Search"}
		<!--
		<form method="post" class="searchform" action="#">
			<p><input type="text" name="search_query" class="textbox" />
  			<input type="submit" name="search" class="button" value="Hledej" /></p>
		</form>-->
			
		<!-- Menu Tabs -->
		<ul>
			<li id="current"><a href="{$base}"><span>{tg}Homepage{/tg}</span></a></li>
			<li><a href="{linkto package=Basic controller=Articles action=actionDetail filters='url=?' values='about' onempty=#}"><span>{tg}About article{/tg}</span></a></li>
			<li><a href="{linkto package=Basic controller=Articles action=actionDetail filters='url=?' values='about-shopping' onempty=#}"><span>{tg}About shopping article{/tg}</span></a></li>
			<li><a href="{linkto package=Commodity controller=References action=actionReferencesList}"><span>{tg}References list{/tg}</span></a></li>
			<li><a href="{linkto package=Basic controller=Articles action=actionDetail filters='url=?' values='contact' onempty=#}"><span>{tg}Contact Article{/tg}</span></a></li>
		</ul>	
													
	</div>
	
<!-- footer starts here -->	
<div id="footer">
	
	<div class="footer-left">
		<p class="align-left">			
		&copy; 2009 <a href="http://www.wild-web.eu/"><strong>Wild-web.eu</strong></a> |
		Design by <a href="http://www.styleshout.com/">styleshout</a> &amp; <a href="http://www.wild-web.eu/">Wild-Web.eu</a> |
		Valid <a href="http://validator.w3.org/check/referer">XHTML</a> |
		<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>
		</p>		
	</div>
	
	<div class="footer-right">
		<p class="align-right">
		<a href="{$root}">{tg}Homepage{/tg}</a>&nbsp;|&nbsp;
  		<a href="{linkto controller=Sitemap action=actionSitemap}">{tg}Sitemap{/tg}</a>&nbsp;|&nbsp;
  		<a href="{linkto package=Commodity controller=Products action=actionRss}">{tg}RSS Feed{/tg}</a>
		</p>
	</div>
	
</div>
<!-- footer ends here -->
	
<!-- wrap ends here -->
</div>

{include file='Base.part.adminBox.tpl' }

{include file='Base.part.pageFooter.tpl' }

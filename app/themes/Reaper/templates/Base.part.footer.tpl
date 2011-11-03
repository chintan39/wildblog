		{tp}All page note.{/tp}
		</div>	
			
		<div id="rightbar">
			
			{require file=part.references package=Commodity}
			{require file=part.actions package=Commodity}
			{require file=part.favourites package=Commodity}
			{require file='part.advertisements' package=Basic}
			{require file='part.htmlAreas' package=Basic}
			
			<div class="pause"></div>
		</div>
		
		</div><!-- mainleft -->
		
		<div id="sidebar" >							
				
			{require file=part.articlesMenu package=Basic}
			{require file=part.categoriesMenu package=Commodity}
			{require file=part.manofacturersList package=Commodity}
			
		</div><!-- sidebar -->
			
		<div id="headerphoto">
		<div id="name">{tp}header page name{/tp}</div>
		<div id="address">{tp}header page street<br />header page city<br />header page phone<br />header page email{/tp}</div>	
      {require file='part.navigation'}
		</div>
		
	<!-- content-wrap ends here -->		
	</div>

	<div id="header">				
			
		<h2 id="logo"><a href="">{tp}header top title{/tp}</a></h2>	
		<h2 id="slogan">{tp}header top subtitle{/tp}</h2> 
		
		{require file='part.searchForm' searchFormClass="searchform" searchFormSubmit="Search"}
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

{require file='part.footer' theme=Common}


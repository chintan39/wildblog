			<!-- /main -->	
			</div>
		
			<!-- sidebar -->
			<div id="sidebar">
							
                <div class="sidemenu">
					<h3>{tg}Main menu{/tg}</h3>
					{require file='part.allPagesMenus' package=Basic menuName='top_menu'}
				</div>

				{*
				<div class="sidemenu">
					{require file='part.partnerLinks' package=LinkBuilding}
				</div>
				
				{require package=Gallery file='part.galleriesList'}
				*}
				
			<!-- /sidebar -->				
			</div>		
			
		<!-- /content -->	
		</div>				


	<!-- /content-wrap -->	
	</div>
	
	<!-- header -->
	<div id="header">			
	
		<a name="top"></a>
		
		<h1 id="logo-text"><a href="{$base}" title="{tg}Home{/tg}">{tp}header top title{/tp}</a></h1>
		<p id="slogan">{tp}header top subtitle{/tp}</p>
		
		<div id="nav">
			{require file='part.allPagesMenus' package=Basic menuName='top_menu'}
		</div>		
		
		<p id="rss-feed"><a href="{linkto package=Blog controller=Posts action=actionRss}" class="feed">{tg}RSS feed{/tg}</a></p>
		
		<form id="quick-search" action="{$base}search/" method="get" >
			<p>
			<label for="qsearch">{tg}Search{/tg}:</label>
			<input class="tbox vanish-onclick" id="qsearch" type="text" name="s" value="{tg}Search on the web...{/tg}" title="{tg}Search on the web...{/tg}" />
			<input class="btn" alt="Search" type="image" name="searchsubmit" title="Search" src="app/themes/Jungleland/images/search.png" />
			</p>
		</form>	
						
		<div id="namedays">
			{require file='part.nameDays' package=Basic}
		</div>
		
	<!-- /header -->					
	</div>
	
<!-- /wrap -->
</div>

<!-- footer -->	
<div id="footer">	

	<!-- footer-outer -->	
	<div id="footer-outer" class="clear"><div id="footer-wrap">
	
		<div class="col-a">
				
			{require file='part.shortContact' package=Basic}
			
		</div>
		
		<div class="col-a">			
			
			<h3>{tg}Interesting links{/tg}</h3>
			
			<div class="footer-list">
				{require file='part.allPagesMenus' package=Basic menuName='top_menu'}
			</div>					
				
		</div>
		
		<div class="col-a">
		
			<h3>{tg}Follow Us{/tg}</h3>
			
			<div class="footer-list">
				<ul>				
					<li><a href="{linkto package=Blog controller=Posts action=actionRss}" class="rssfeed">{tg}RSS Feed{/tg}</a></li>
					<li><a href="mailto:petra.pavlickova@seznam.cz" class="email">Email</a></li>
					<li><a href="#" class="facebook">Facebook</a></li>
				</ul>
			</div>					
				
		</div>		
	
		<div class="col-b">
		
		{require file='part.personalInfo' package=Basic}
			
		</div>		
		
		<div class="fix"></div>
		
		<!-- footer-bottom -->		
		<div id="footer-bottom">
	
			<div class="bottom-left">
				<p>
				Powered by <a href="http://code.google.com/p/wildblog/" title="wild-blog">wildblog project</a>,
				Jan Horák, <a href="http://www.wild-web.eu" title="wild-web">wild-web.eu</a>
				&copy; {$now|date_format:"%Y"} | <a href="{$base}admin/">Administrace</a>
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				<a href="http://www.bluewebtemplates.com/" title="Website Templates">website templates</a> by <a href="http://www.styleshout.com/">styleshout</a>
				</p>
			</div>		
	
			<div class="bottom-right">
				<p>		
					<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | 
		   		<a href="http://validator.w3.org/check/referer">XHTML</a>	|			
					<a href="{$base}">Home</a> |
					<strong><a href="#top" class="back-to-top">Back to Top</a></strong><br />								
				{tg}Visitors{/tg}: <!-- webdiffer-no-log-begin -->{$visitorsCount}<!-- webdiffer-no-log-end -->
				</p>
			</div>

		<!-- /footer-bottom -->		
		</div>
	
	<!-- /footer-outer -->		
	</div></div>		

<!-- /footer -->
</div>

{require file='part.htmlAreas' package=Basic}

{require file='part.adminBox' theme=Common}

{require file='part.footer' theme=Common}

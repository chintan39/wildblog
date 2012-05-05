			<!-- /main -->	
			</div>
		
			<!-- sidebar -->
			<div id="sidebar">
							
                <div class="sidemenu">
					<h3>{tg}Main menu{/tg}</h3>
					{include file='Basic.part.allPagesMenus.tpl' package=Basic menuName='top_menu'}
				</div>

				{*
				<div class="sidemenu">
					{include file='LinkBuilding.part.partnerLinks.tpl' package=LinkBuilding}
				</div>
				
				{include package=Gallery file='Gallery.part.galleriesList.tpl'}
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
			{include file='Basic.part.allPagesMenus.tpl' package=Basic menuName='top_menu'}
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
			{include file='Basic.part.nameDays.tpl' package=Basic}
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
				
			{include file='Basic.part.shortContact.tpl' package=Basic}
			
		</div>
		
		<div class="col-a">			
			
			<h3>{tg}Interesting links{/tg}</h3>
			
			<div class="footer-list">
				{include file='Basic.part.allPagesMenus.tpl' package=Basic menuName='top_menu'}
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
		
		{include file='Basic.part.personalInfo.tpl' package=Basic}
			
		</div>		
		
		<div class="fix"></div>
		
		<!-- footer-bottom -->		
		<div id="footer-bottom">
	
			<div class="bottom-left">
				<p>
				
				{include file='Base.part.wwFooter.tpl' sep=' '}
				
				<br />
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

{include file='Basic.part.htmlAreas.tpl' package=Basic}

{include file='Base.part.adminBox.tpl' }

{include file='Base.part.footer.tpl' }

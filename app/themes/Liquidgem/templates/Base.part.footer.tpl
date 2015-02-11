</div>

<!-- TO MAKE THE PHP FORM WORK, ALL YOU NEED TO DO IS OPEN UP THE FILE CALLED 'submitemail.php' AND CHANGE WHERE IT SAYS 'your email goes here' -->

<!-- DON'T TOUCH THIS SECTION -->

<footer id="footer">
	<div class="wrapper">
    	<section class="left">
    	<h4>{tg}Contact{/tg}</h4>
            <div id="formwrap">

{include file='Basic.part.contactForm.tpl' package=Basic}
	
            </div>
            <div id="error"></div>
        </section>

<!-- DON'T TOUCH THIS SECTION END -->        
        
    	<section class="right"> <!-- Social Icons Start -->
    	<section class="left">
<h4>{tg}Recommend{/tg}:</h4>
{tg}Recommend text box{/tg}
        </section> <!-- Social Icons End -->
    	<section class="right social"> <!-- Social Icons Start -->
{*
 		       <a href="http://twitter.com"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/twitter.png" width="48" height="48" alt="twitter"></a><!-- Replace with any 32px x 32px icons -->

        <a href="http://youtube.com"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/youtube.png" width="48" height="48" alt="youtube"></a><!-- Replace with any 32px x 32px icons -->
*}
	<a href="http://www.addtoany.com/add_to/google_bookmarks?linkurl={$thisLink|urlencode}&linkname={$pageTitle|default:$title|urlencode}"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/google.png" width="48" height="48" alt="google"></a><!-- Replace with any 32px x 32px icons -->
        <a href="https://www.facebook.com/pages/Michaela-Luk%C5%A1%C3%AD%C4%8Dkov%C3%A1/1578852452329906"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/facebook.png" width="48" height="48" alt="facebook"></a><!-- Replace with any 32px x 32px icons -->
	 <a href="http://www.mapy.cz/s/fMDo"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/map.png" width="48" height="48" alt="facebook"></a>

        </section> <!-- Social Icons End -->
        </section> <!-- Social Icons End -->
    </div>
    <div class="clearfix"></div>
	<div class="wrapper" style="position: relative;">
    	<section class="social" style="position: absolute; bottom: 5px; right: 5px;"> <!-- Social Icons Start -->
      {include file='Base.part.wwFooter.tpl' sep=' ' nopopuplogin=1}
        </section> <!-- Social Icons End -->
        </div>
    <div class="clearfix"></div>
</footer>

<!-- SLIDESHOW SCRIPT START -->
<script type="text/javascript">
$("#slider").carouFredSel({
	responsive	: true,
	scroll		: {
		fx			: "crossfade",
		easing		: "swing",
		duration	: 1000,
		
	},
	items		: {
		visible		: 1,
		height		: "27%"
	}
});
</script>
<!-- SLIDESHOW SCRIPT END -->
{include file='Basic.part.htmlAreas.tpl' package=Basic}
{include file='Base.part.adminBox.tpl'}

</body>
</html>
<!-- Thanks for looking at Liquid Gem! I hope you find it useful :) -->

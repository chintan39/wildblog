</div>

<!-- TO MAKE THE PHP FORM WORK, ALL YOU NEED TO DO IS OPEN UP THE FILE CALLED 'submitemail.php' AND CHANGE WHERE IT SAYS 'your email goes here' -->

<!-- DON'T TOUCH THIS SECTION -->

<footer id="footer">
	<div class="wrapper">
    	<section class="left">
    	<h4>Contact</h4>
            <div id="formwrap">
                <form method="post" id="submitform" action="submitemail.php" >
                            <input type="text" class="formstyle" title="{tg}Name{/tg}" name="name" />
                            <input type="text" class="formstyle" title="{tg}Email{/tg}" name="email" />
                            <textarea name="message" title="{tg}Message{/tg}"></textarea>
                            <input class="formstyletwo" type="submit" value="Send">  
                </form>
            </div>
            <div id="error"></div>
        </section>

<!-- DON'T TOUCH THIS SECTION END -->        
        
    	<section class="right social"> <!-- Social Icons Start -->
		<a href="http://plus.google.co.uk"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/google.png" width="32" height="32" alt="google"></a><!-- Replace with any 32px x 32px icons -->
        <a href="http://youtube.com"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/youtube.png" width="32" height="32" alt="youtube"></a><!-- Replace with any 32px x 32px icons -->
        <a href="http://facebook.com"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/facebook.png" width="32" height="32" alt="facebook"></a><!-- Replace with any 32px x 32px icons -->
        <a href="http://twitter.com"><img class="icon" src="{$base}app/themes/{$generalTheme}/images/icons/twitter.png" width="32" height="32" alt="twitter"></a><!-- Replace with any 32px x 32px icons -->
        </section> <!-- Social Icons End -->
    	<section class="right social"> <!-- Social Icons Start -->
        <p>
      {include file='Base.part.wwFooter.tpl' sep=' ' nopopuplogin=1}
        </p>
        </section> <!-- Social Icons End -->
        <p>lets get social - </p>
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

{include file='Base.part.header.tpl'}

{if $homepageArticle}

    <header>	<!-- Header Title Start -->
    	<h1>{tp}homepage slider text{/tp}</h1>
    </header>	<!-- Header Title End -->
    
    <aside id="about" class=" left"> <!-- Text Section Start -->
    	<h3>{tg}about me{/tg}</h3><!-- Replace all text with what you want -->
    	<p><img src="media/michaela-luksickova.jpg" /></p>
    </aside>
    <aside class="right">
    	<h3>{$article->title}</h3>
    	{$article->text}
    </aside>
    <div class="clearfix"></div> <!-- Text Section End -->
    

{else}
 

    <header><!-- Work Showcase Section Start -->
    
    	<h1>{$article->title}</h1>
    </header>
    
    <section id="workbody"><!-- Project images start -->
{$article->text}
    </section><!-- Project images end -->
    
{/if}

    <hr/>	<!-- Horizontal Line -->

    <section id="news"> <!-- Work Links Section Start -->
    <aside id="about" class=" left"> <!-- Text Section Start -->
    	<h3>Novinka</h3><!-- Replace all text with what you want -->
    	<p>Text novinky a jeho kratky popis, spolu s datumem a mistem konani.</p>
    </aside>
    <aside class="right">
    	<h3>Workshop</h3>
    	<p>Text workshopu a jeho kratky popis, spolu s datumem a mistem konani.</p>
    </aside>
    <div class="clearfix"></div> <!-- Text Section End -->
    </section> <!-- Work Links Section End -->

    <hr/>	<!-- Horizontal Line -->

{include file='Base.part.footer.tpl'}


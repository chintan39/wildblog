{include file='Base.part.header.tpl'}

{if $homepageArticle}

    <header>	<!-- Header Title Start -->
    	<h1 style="text-align:center;color: #b518c3">{tp}homepage slider text{/tp}</h1>
    </header>	<!-- Header Title End -->
    
{include file='Basic.part.headerimage.tpl'}

{*    <aside id="about" class=" left"> <!-- Text Section Start -->
    	<h3>{tg}about me{/tg}</h3><!-- Replace all text with what you want -->
    	<p><img src="media/michaela-luksickova.jpg" /></p>
    </aside>
    <aside class="right">
    	<h3>{$article->title}</h3>
    	{$article->text}
    </aside>
    <div class="clearfix"></div> <!-- Text Section End -->
*}

    <header><!-- Work Showcase Section Start -->

        <h1>{$article->title}</h1>
    </header>
        {$article->text}
    
    <hr/>	<!-- Horizontal Line -->

{include file='Basic.part.recentNews.tpl'}

{else}
 

{include file='Basic.part.headerimage.tpl'}

    <header><!-- Work Showcase Section Start -->
    
    	<h1>{$article->title}</h1>
    </header>

     <section id="workbody"><!-- Project images start -->
{$article->text}
    </section><!-- Project images end -->
    
{/if}

    <div class="clearfix"></div>

    <hr/>	<!-- Horizontal Line -->

{include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}

{include file='Base.part.footer.tpl'}


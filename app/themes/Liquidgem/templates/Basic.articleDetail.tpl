{include file='Base.part.header.tpl'}

{if $homepageArticle}

    <header>	<!-- Header Title Start -->
    	<h1>Hello there, I'm <span>&quot;Your Name&quot;</span>. Welcome to my design portfolio!</h1>
        <h2>&ndash; Photographer and Web Developer &ndash;</h2>
    </header>	<!-- Header Title End -->
    <section id="slideshow">	<!-- Slideshow Start -->
        <div class="html_carousel">
			<div id="slider">
            
				<div class="slide">
					<img src="{$base}media/slideshow/sliderimage1.jpg" width="3000" height="783" alt="image 1"/><!-- Replace these images with your own but make sure they are 3000px wide and 783px high or the same ration -->
				</div><!--/slide-->
                
				<div class="slide">
					<img src="{$base}media/slideshow/sliderimage2.jpg" width="3000" height="783" alt="image 2"/><!-- Replace these images with your own but make sure they are 3000px wide and 783px high or the same ration -->
				</div><!--/slide-->
                
                <div class="slide">
					<img src="{$base}media/slideshow/sliderimage3.jpg" width="3000" height="783" alt="image 3"/><!-- Replace these images with your own but make sure they are 3000px wide and 783px high or the same ration -->
				</div><!--/slide-->
                
			</div><!--/slider-->
			<div class="clearfix"></div>
		</div><!--/html_carousel-->
    </section>	<!-- Slideshow End -->
    
    
    <aside id="about" class=" left"> <!-- Text Section Start -->
    	<h3>about me</h3><!-- Replace all text with what you want -->
    	<p>Hey there, my name is &quot;Your Name&quot; and I am a photographer and web developer! This is my brand new portfolio. It's super cool because it's completely responsive! That means you can re-size it to whatever size you like and it always looks great. Have a look around and enjoy.</p>
    </aside>
    <aside class="right">
    	<h3>my work</h3>
    	<p>Below, you will be able to find lots of my work. I take loads of pretty pictures and I also make websites. If you like what you see then you can contact me below! Maybe you would like to hire me or just have a chat, either way, I look forward to hearing from you.</p>
    </aside>
    <div class="clearfix"></div> <!-- Text Section End -->
    
    
    <section id="work"> <!-- Work Links Section Start -->
    	<div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item.png" alt="image 1"></a><!-- Image must be 400px by 300px -->
            <h3>Skies Of Spain</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item2.png" alt="image 2"></a><!-- Image must be 400px by 300px -->
        	<h3>Beautiful Bahrain</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item3.png" alt="image 3"></a><!-- Image must be 400px by 300px -->
        	<h3>Wild Stripes</h3><!--Title-->
            <p>photo manipulation</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item4.png" alt="image 4"></a><!-- Image must be 400px by 300px -->
        	<h3>Lazy Days</h3><!--Title-->
            <p>photo manipulation</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item5.png" alt="image 5"></a><!-- Image must be 400px by 300px -->
        	<h3>Trapped</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        	<div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item6.png" alt="image 6"></a><!-- Image must be 400px by 300px -->
            <h3>Quad-Core</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item7.png" alt="image 7"></a><!-- Image must be 400px by 300px -->
        	<h3>Retro Blast</h3><!--Title-->
            <p>illustration</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item8.png" alt="image 8"></a><!-- Image must be 400px by 300px -->
        	<h3>Gates Of The Sun</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item9.png" alt="image 9"></a><!-- Image must be 400px by 300px -->
        	<h3>Winter Touch</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
         <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item10.png" alt="image 10"></a><!-- Image must be 400px by 300px -->
        	<h3>Burn</h3><!--Title-->
            <p>photo manipulation</p><!--Category-->
        </div><!--/item-->
        
        <div class="clearfix"></div>
    </section> <!-- Work Links Section End -->
    
    
    <section id="bottom"> <!-- Last Words Section Start -->
    	<h3>Thanks for looking at my new website!</h3>
    </section><!-- Last Words Section End-->

{else}
 

    <header><!-- Work Showcase Section Start -->
    
    	<h1>Skies Of Spain</h1><!-- Title of project -->
        <h2>photography</h2><!-- Category of project -->
        <!-- Description of project start -->
        <p>Spain has always been a favorite country of mine because of the absolutely stunning skies. I am mesmerized by the dazzling colours and it is one of my favorite places to take photos. Below are my three favorite photographs that I have taken of this glorious setting.</p>
        <!-- Description of project end -->
    </header>
    
    <section id="workbody"><!-- Project images start -->
    	<img src="{$base}media/work/SkiesOfSpain/sky1.JPG" alt="sky1"><!-- Use whatever images you like - they will automatically fit the width of the page -->
        <h5>&ndash; Volcanic Skies</h5><!-- Image title -->
        <img src="{$base}media/work/SkiesOfSpain/sky2.JPG" alt="sky2"><!-- Use whatever images you like - they will automatically fit the width of the page -->
        <h5>&ndash; Godly Light</h5><!-- Image title -->
        <img src="{$base}media/work/SkiesOfSpain/sky3.JPG" alt="sky3"><!-- Use whatever images you like - they will automatically fit the width of the page -->
        <h5>&ndash; Pale Evening</h5><!-- Image title -->
    </section><!-- Project images end -->
    
    <hr/>	<!-- Horizontal Line -->
    
    
    
    <section id="work"> <!-- Work Links Section Start -->
    	<div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item.png" alt="image 1"></a><!-- Image must be 400px by 300px -->
            <h3>Skies Of Spain</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item2.png" alt="image 2"></a><!-- Image must be 400px by 300px -->
        	<h3>Beautiful Bahrain</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item3.png" alt="image 3"></a><!-- Image must be 400px by 300px -->
        	<h3>Wild Stripes</h3><!--Title-->
            <p>photo manipulation</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item4.png" alt="image 4"></a><!-- Image must be 400px by 300px -->
        	<h3>Lazy Days</h3><!--Title-->
            <p>photo manipulation</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item5.png" alt="image 5"></a><!-- Image must be 400px by 300px -->
        	<h3>Trapped</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        	<div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item6.png" alt="image 6"></a><!-- Image must be 400px by 300px -->
            <h3>Quad-Core</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item7.png" alt="image 7"></a><!-- Image must be 400px by 300px -->
        	<h3>Retro Blast</h3><!--Title-->
            <p>illustration</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item8.png" alt="image 8"></a><!-- Image must be 400px by 300px -->
        	<h3>Gates Of The Sun</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
        <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item9.png" alt="image 9"></a><!-- Image must be 400px by 300px -->
        	<h3>Winter Touch</h3><!--Title-->
            <p>photography</p><!--Category-->
        </div><!--/item-->
        
         <div class="item">
        	<a href="work-template.html"><img src="{$base}media/work/thumbs/item10.png" alt="image 10"></a><!-- Image must be 400px by 300px -->
        	<h3>Burn</h3><!--Title-->
            <p>photo manipulation</p><!--Category-->
        </div><!--/item-->
        
        <div class="clearfix"></div>
    </section> <!-- Work Links Section End -->

{/if}

{include file='Base.part.footer.tpl'}


{include file='Base.part.header.tpl'}

{if $homepageArticle}

		<!-- Banner Wrapper -->
			<div id="banner-wrapper">

				<!--
				
					The slider's images (as well as its behavior) can be configured
					at the top of "../js/init.js".
				
				-->

				<div id="slider">
					<div class="caption">
						<h2>Nově otevřená bezkontaktní automyčka</h2>
						<p>Starém Hradiště u Pardubic, Hradecká 545</p>
					</div>
				</div>

			</div>

		<!-- Main Wrapper -->
			<div id="main-wrapper">

				<!-- Main -->
					<div id="intro" class="container">
						<div class="row">
							<section class="4u">
								<span class="number">01</span>
								<header>
									<h2>{tp}Program#1{/tp}</h2>
								</header>
								<p>{tp}Program description #1{/tp}</p>
							</section>
							<section class="4u">
								<span class="number">02</span>
								<header>
									<h2>{tp}Program#2{/tp}</h2>
								</header>
								<p>{tp}Program description #2{/tp}</p>
							</section>
							<section class="4u">
								<span class="number">03</span>
								<header>
									<h2>{tp}Program#3{/tp}</h2>
								</header>
								<p>{tp}Program description #3{/tp}</p>
							</section>
						</div>
						<div class="row">
							<section class="6u">
								<span class="number">04</span>
								<header>
									<h2>{tp}Program#4{/tp}</h2>
								</header>
								<p>{tp}Program description #4{/tp}</p>
							</section>
							<section class="6u">
								<span class="number">05</span>
								<header>
									<h2>{tp}Program#5{/tp}</h2>
								</header>
								<p>{tp}Program description #5{/tp}</p>
							</section>
						</div>
						<div class="actions">
							<a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=6}" class="button button-big">Mycí fáze</a>
							<a href="{linkto package=Basic controller=News action=actionNewsList}" class="button button-big button-alt">Akce</a>
						</div>
					</div>

			</div>

{else}
 

		<!-- Main Wrapper -->
			<div id="main-wrapper">
				<div id="main" class="container">
					
					<div class="row">
					
						<!-- Content -->
							<div id="content" class="8u">
								<article>
									<header>
										<h2>{$article->title}</h2>
									</header>

{$article->text}

{include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$article}

{if $article->id == 5}
<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10245.54658715685!2d15.774591115863258!3d50.06032022525783!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470dcd2133b9f129%3A0x5e0f5847d8e0cb9f!2sHradeck%C3%A1+545%2C+Pr%C5%AFmyslov%C3%A1+zona+F%C3%A1blovka%2C+533+52+Pardubice-Pardubice+II!5e0!3m2!1scs!2scz!4v1411937980484" width="100%" height="450" frameborder="0" style="border:0"></iframe>
{/if}
								</article>
							</div>
						
						<!-- Sidebar -->
							<div id="sidebar" class="4u">
								<section class="section-padding">
        	{include file='Basic.part.recentNews.tpl'}
								</section>
	
								
							</div>
						
					</div>
					
				</div>
			</div>


{/if}

{include file='Base.part.footer.tpl'}


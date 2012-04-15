					</div>
					<div id="left">
					{require file='part.menuLeft'}
					</div>

					<div class="clear"></div>
					
				    <div id="header-langs">{tg}Content language:{/tg}
					{require file='part.languages' languages=$frontendLanguages}
					</div><!-- header-langs -->

					{require file='part.imageManagerIcons'}
					
				</div>
				<div id="header">
					<a href="{$base}" class="title">{$projectTitle}</a>
					{require file='part.userInfo'}
					
				    <div id="header-langs2">{tg}Admin language:{/tg}
					{require file='part.languages' languages=$backendLanguages}
					</div><!-- header-langs -->
					
					{require file='part.help'}
				</div>
			</div>
			<div id="lower">
				Powered by <a href="http://code.google.com/p/wildblog/" title="wild-blog">wildblog project</a>,
				Jan Horák, <a href="http://www.wild-web.eu" title="wild-web">wild-web.eu</a>
				&copy; 2009-{$now|date_format:"%Y"}
			</div>
		</div>
	</div>
	
{require file='part.adminBox' theme=Common}

{require file='part.footer' theme=Common}

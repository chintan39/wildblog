					</div>
					<div id="left">
					{include file='Base.part.menuLeft'}
					</div>

					<div class="clear"></div>
					
				    <div id="header-langs">{tg}Content language:{/tg}
					{include file='Base.part.languages' languages=$frontendLanguages}
					</div><!-- header-langs -->

				</div>
				<div id="header">
					<a href="{$base}" class="title">{$projectTitle}</a>
					{include file='Base.part.userInfo'}
					
				    <div id="header-langs">{tg}Admin language:{/tg}
					{include file='Base.part.languages' languages=$backendLanguages}
					</div><!-- header-langs -->
					
				</div>
			</div>			
			<div id="lower">
				Jan Horák, <a href="http://www.wild-web.eu" title="wild-web">wild-web.eu</a> &copy; 2009 
			</div>
		</div>

{include file='Base.part.adminBox' }

{include file='Base.part.footer' }


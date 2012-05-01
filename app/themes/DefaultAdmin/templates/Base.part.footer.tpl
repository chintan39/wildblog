					</div>
					<div id="left">
					{include file='Base.part.menuLeft.tpl'}
					</div>

					<div class="clear"></div>
					
				    <div id="header-langs">{tg}Content language:{/tg}
					{include file='Base.part.languages.tpl' languages=$frontendLanguages}
					</div><!-- header-langs -->

					{include file='Base.part.imageManagerIcons.tpl'}
					
				</div>
				<div id="header">
					<a href="{$base}" class="title">{$projectTitle}</a>
					{include file='Base.part.userInfo.tpl'}
					
				    <div id="header-langs2">{tg}Admin language:{/tg}
					{include file='Base.part.languages.tpl' languages=$backendLanguages}
					</div><!-- header-langs -->
					
					{include file='Base.part.help.tpl'}
				</div>
			</div>
			<div id="lower">
				Powered by <a href="http://code.google.com/p/wildblog/" title="wild-blog">wildblog project</a>,
				Jan Horák, <a href="http://www.wild-web.eu" title="wild-web">wild-web.eu</a>
				&copy; 2009-{$now|date_format:"%Y"}
			</div>
		</div>
	</div>
	
{include file='Base.part.adminBox.tpl'}

{include file='Base.part.pageFooter.tpl'}

	<!-- main content end -->
	
    </div><!-- content -->
    </div><!-- left -->
    
    <div id="right">
      {include file='Basic.part.htmlAreas.tpl' package=Basic}
    </div><!-- right -->
    
	<div class="clear"></div>
  </div><!-- middle -->
  
  <div id="header">
    {tp}header top quot{/tp}
  </div><!-- header -->
  
</div><!-- upper -->
</div><!-- page -->

<hr />

{tg}Powered by{/tg} <a href="http://code.google.com/p/wildblog/" title="wildblog">wildblog project</a>,
Honza Horák, <a href="http://www.wild-web.eu" title="www.wild-web.eu">wild-web.eu</a>
&copy; {$now|date_format:"%Y"} | <a href="{$base}admin/">{tg}Administration{/tg}</a>

{include file='Base.part.adminBox.tpl' }

{include file='Base.part.footer.tpl' }


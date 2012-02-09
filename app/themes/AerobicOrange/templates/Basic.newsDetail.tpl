{require file='part.header'}

	<h1>{$news->title}</h1>
	<div class="date">{$news->published|date_format:"%e"}. {$news->published|date_format:"%m"|month_format:"%m"}. {$news->published|date_format:"%Y"}</div>
	{$news->text|addlinks}
	<div class="clear"></div>
	{require package=Base file='part.editItem' itemPackage=Basic itemController=News itemAction=actionEdit itemItem=$news}

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4ca362e0566b35bb"></script>
<!-- AddThis Button END -->

{require file='part.footer'}

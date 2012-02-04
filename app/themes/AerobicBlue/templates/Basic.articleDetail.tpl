{if $article and $article->id eq 10 or $article and $article->id eq 15 or $article and $article->id eq 18} {*only for rozvrh*}
{assign var=widepage value=1}
{/if}
{require file='part.header'}

	<h1>{$article->title}</h1>
	{$article->text}
	{require package=Base file='part.editItem' itemPackage=Basic itemController=Articles itemAction=actionEdit itemItem=$article}

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4ca362e0566b35bb"></script>
<!-- AddThis Button END -->

{require file='part.footer'}


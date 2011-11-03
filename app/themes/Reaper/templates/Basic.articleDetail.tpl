{require file='part.header'}

	<div class="article">
	<h1>{$article->title}</h1>
	{$article->text}
	</div>

{require file=part.contactForm package=Basic}

{require file='part.footer'}


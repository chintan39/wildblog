{require file='part.header'}

	<h1>{$news->title}</h1>
	<div class="date">{$news->published|date_format:"%m/%e"}</div>
	{$news->text|addlinks}
	<div class="clear"></div>
	{require package=Base file='part.editItem' itemPackage=Basic itemController=News itemAction=actionEdit itemItem=$news}

{require file='part.footer'}


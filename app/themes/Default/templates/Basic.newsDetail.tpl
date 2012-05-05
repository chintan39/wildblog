{include file='Base.part.header.tpl'}

	<h1>{$news->title}</h1>
	<div class="date">{$news->published|date_format:"%mnamelong /%e"}</div>
	{$news->text|addlinks}
	<div class="clear"></div>
	{include  file='Basic.part.editItem.tpl' itemPackage=Basic itemController=News itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$news}

{include file='Base.part.footer.tpl'}


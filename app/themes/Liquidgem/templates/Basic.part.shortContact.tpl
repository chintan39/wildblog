{if $shortContact}
									<header>
										<h2>{$shortContact->title}</h2>
									</header>
									{$shortContact->text}
									<a href="{linkto package=Basic controller=Articles action=actionDetail dataItem=5}" class="button">{tg}See more{/tg}</a>
{include  file='Base.part.editItem.tpl' itemPackage=Basic itemController=Articles itemAction=actionEdit itemActionSimple=actionSimpleEdit itemItem=$shortContact}
{/if}


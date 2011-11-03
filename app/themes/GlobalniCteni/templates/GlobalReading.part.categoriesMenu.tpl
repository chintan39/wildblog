{strip}
{if $categoriesMenu->data.items}
{if $article->id eq 58 or $createFormId}
<ul class="sidemenu">
{foreach from=$categoriesMenu->data.items item=category}
<li{if $category->id eq $createFormId} class="active"{/if}><a href="{$category->link}">{$category->title}</a></li>
{/foreach}
</ul>
{/if}
{/if}
{/strip}

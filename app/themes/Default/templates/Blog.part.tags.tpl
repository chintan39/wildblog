{strip}
{if $tags}<div class="tags">{tg}Tags{/tg}:&nbsp;
{foreach from=$tags item=tag name=tags}
	{$tag->title}
	{if !$smarty.foreach.tags.last} | {/if}
{/foreach}
</div>
{/if}
{/strip}


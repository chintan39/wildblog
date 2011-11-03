{if $mainAreas->data.items}
{foreach from=$mainAreas->data.items item=item}
{$item->text}
{/foreach}
{/if}


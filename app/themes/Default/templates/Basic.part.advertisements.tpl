{if $mainAdvertisements}
{if $mainAdvertisements->data.items}
{foreach from=$mainAdvertisements->data.items item=item}
{$item->text}
{/foreach}
{/if}
{/if}


{if $mainAdvertisements}
<div class="box">
<div class="light advertisment">
{if $mainAdvertisements->data.items}
{foreach from=$mainAdvertisements->data.items item=item}
{$item->text}
{/foreach}
{/if}
</div><!-- light -->
</div><!-- box -->
{/if}


<!-- webdiffer-no-log-begin -->
<div class="todayDate">{tg}Today is{/tg} <strong>{$today}</strong></div>
{if $todayName}
<div class="todayName">{tg}Today name day{/tg}: <strong>{$todayName}</strong></div>
{/if}
{if $todayEvent}
<div class="todayEvent">
{strip}
{foreach from=$todayEvent item=item name=fn}
{if not $smarty.foreach.fn.first}, {/if}
{if $item->days}{tg}In{/tg} <strong>{$item->days}</strong> {tg}days{/tg}{else}{tg}Today{/tg}{/if}: <strong>{$item->name}</strong>
{/foreach}
{/strip}
</div>
{/if}
<!-- webdiffer-no-log-end -->


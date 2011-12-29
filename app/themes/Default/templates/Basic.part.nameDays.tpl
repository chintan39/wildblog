<div class="todayDate">{tg}Today is{/tg} {$today}</div>
{if $todayName}
<div class="todayName">{tg}Name day{/tg}: {$todayName}</div>
{/if}
{if $todayEvent}
{* TODO: add parameters and use one sentese *}
<div class="todayEvent">{tg}In{/tg} {$todayEvent->days} {tg}days{/tg}: {$todayEvent->name}</div>
{/if}


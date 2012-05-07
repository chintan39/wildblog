{include file='Base.part.header.tpl'}

<h1>{$title|default:'Reservation - choose rooms':tg}</h1>


We will probably need virtual model with dinamicly generated room
together with preview action we can have more virtual models, 
first only with rooms, second with info without registration, third for logged user, ...

<form method="get" action="">
<p>
<input type="text" value="" name="nights" />
<input type="text" value="" name="date_from" />
</p>
<div class="clear"></div>
<table>
<tr><th>Room</th>
{foreach from=$dates item=date}
<th>{$date}</th>
{/foreach}
<th>{tg}Book beds{/tg}</th>
</tr>
{foreach from=$rooms->data.items item=room}
<tr><td>{$room->text}</td>
{foreach from=$dates item=date}
<td>{if $room->info.$date->free}{$room->info.$date->price|price}{else}{tg}Full{/tg}{/if}</td>
{/foreach}
<td>
<select name="room[{$room->id}]">
{foreach from=$room->beds item=bedCount}
<option value="{$bedCount}">{$bedCount}</option>
{/foreach}
</select>
</td>
</tr>
{/foreach}
</table>
<input type="submit" value="{tg}Submit{/tg}" name="submit" />
</form>

{include file='Base.part.footer.tpl'}


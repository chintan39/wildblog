{include file='Base.part.header'}

{if $title}
<h1>{$title}</h1>
{/if}

<h2>{tg}Title{/tg}:</h2>
<div class="value">
{$message->title}
</div>

<h2>{tg}Text{/tg}:</h2>
<div class="value">
{$message->text}
</div>

<h2>{tg}Recepients{/tg}:</h2>
<div class="value">
{if $message->recipients}
{foreach from=$message->recipients item=recipient name=rec}
{$recipient->email}{if not $smarty.foreach.rec.last}, {/if}
{/foreach}
{/if}
</div>

<div class="sending">
<a href="{linkto package=Newsletter controller=Messages action=actionSend dataItem=$message}" class="sending">{tg}Send newsletter{/tg}</a>
</div>

{include file='Base.part.footer'}


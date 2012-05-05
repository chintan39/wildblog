{include file='Base.part.header.tpl'}
{if $title}<h1>{$title}</h1>{/if}

{foreach from=$results item=res}
<div class="test{if $res->result}Pass{else}Fail{/if}">
{if $res->result}Pass: {else}Fail: {/if}
{$res->text}
</div>
{/foreach}

{include file='Base.part.footer.tpl'}


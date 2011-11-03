{if $help}
<div id="help_container" style="display: none;">
<a href="#" onclick="Effect.toggle('help_container','appear'); return false;" class="help_close"><img src="{$iconsPath}24/remove.png" title="{tg}Close{/tg}" alt="{tg}Close{/tg}" /></a>
{$help|nl2br}
</div>
<a href="#" onclick="Effect.toggle('help_container','appear'); return false;" class="help_icon"><img src="{$iconsPath}32/info.png" title="{tg}Help{/tg}" alt="{tg}Help{/tg}" /></a>
<!-- addScriptaculouse -->
{/if}

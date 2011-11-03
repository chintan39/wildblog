    <form action="{linkto controller=Search action=actionSearch}" method="get"{if $searchFormClass} class="{$searchFormClass}"{/if}>
	<fieldset>
	<input type="submit" name="submit" class="submit" value="{$searchFormSubmit|tg}" />
	<input type="text" name="s"{if not $searchText} class="vanish-onclick"{/if} value="{if $searchText}{$searchText}{else}{tg}search this web...{/tg}{/if}" />
	</fieldset>
	</form>


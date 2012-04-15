{if $actualUserInfo and $actualUserInfo->id and not $requestIsAjax}

<script language="javascript">
var wwDictJSON = '##exportDictJSON##';
</script>

<div id="adminBox">
	<h2 class="admin">{tg}Admin Box{/tg}</h2>
	<a href="{$base}admin/" class="admin" title="{tg}Enter extended administration interface{/tg}">{tg}Admin Interface{/tg}</a>
	<a href="#" onclick="windowPopupAjaxTranslations('{$dictionaryEditLink}', wwDictJSON); return false;" class="dict" title="{tg}Using dictionary you can change translations and static texts on the page{/tg}">{tg}Dictionary Translations{/tg}</a>
	<a href="?{$benchmarkChangeTracking}" class="bench" title="{tg}You can see how efficient and memory demanding the requests are{/tg}">{tg}Benchmark On/Off{/tg}</a>
	<a href="{linkto package=Base controller=Users action=actionLogout}" class="logout" title="{tg}Logout from administration{/tg}">{tg}Logout{/tg}</a>
	<a href="#" onclick="$('adminBox').hide();return false;" class="close" title="{tg}Close This Box{/tg}"></a>
</div>

{/if}

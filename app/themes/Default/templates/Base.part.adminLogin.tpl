<a href="{$base}admin/">{tg}Administration{/tg}</a>{$sep}
{if $actualUserInfo and $actualUserInfo->id}
{tg}Logged:{/tg} <a href="#">{$actualUserInfo->email}</a> {$sep} 
<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging">{tg}Log out{/tg}</a>
{else}
<a href="#" onclick="return windowPopupAjaxGetContent('admin-simple');">{tg}Log in{/tg}</a>
{/if}


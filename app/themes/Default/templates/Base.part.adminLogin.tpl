<a href="{$base}admin/">{tg}administration{/tg}</a>{$sep}
{if $actualUserInfo and $actualUserInfo->id}
{tg}Logged:{/tg} <a href="#">{$actualUserInfo->email}</a> {$sep} 
<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>
{else}
<a href="#" onclick="return windowPopupAjaxGetContent('admin-simple');">{tg}Login{/tg}</a>
{/if}

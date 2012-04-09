{php}
Javascript::addWindows();
{/php}
<a href="{$base}admin/" onclick="return windowPopupAjaxGetContent('admin-simple');">{tg}administration{/tg}</a>
{if $actualUserInfo and $actualUserInfo->id}
{tg}Logged:{/tg} <a href="#">{$actualUserInfo->email}</a> | 
<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging">{tg}Logout{/tg}</a>
{else}
<a href="#" onclick="return windowPopupAjaxGetContent('admin-simple');">{tg}Login{/tg}</a>
{/if}


<a href="{$base}admin/"><img src="{$iconsPath}24/administration.png" alt="{tg}Administration{/tg}" title="{tg}Administration{/tg}" /></a>{$sep}
{if $actualUserInfo and $actualUserInfo->id}
<a href="{linkto package=Base controller=Users action=actionEditProfile}"><img src="{$iconsPath}24/user.png" alt="{$actualUserInfo->email}" title="{$actualUserInfo->email}" /></a> {$sep} 
<a href="{linkto package=Base controller=Users action=actionLogout}" class="logging"><img src="{$iconsPath}24/lock_remove.png" alt="{tg}Log out{/tg}" title="{tg}Log out{/tg}" /></a>
{elseif not $nopopuplogin}
<a href="#" onclick="return windowPopupAjaxGetContent('admin-simple');"><img src="{$iconsPath}24/lock.png" alt="{tg}Log in{/tg}" title="{tg}Log in{/tg}" /></a>
{/if}


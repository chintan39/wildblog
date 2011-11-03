<html><body>
<div id="page" class="emptypage">
{if $title}<h1>{$title}</h1>{/if}
{$action}
<textarea rows="20" cols="80">{$text|htmlspecialchars}</textarea>
<div class="clear"></div>
<pre>
{$text|htmlspecialchars}
</pre>
</div>
</body></html>


{include file='part.rss.header'}
{if $items->data.items}
{foreach from=$items->data.items item=item}
    <item>
      <title>{$item->title}</title>
      <link>{$item->link}</link>
      <guid>{$item->link}</guid>
      <description>{$item->description|default:$item->text|strip_tags|truncate:255}</description>
      <pubDate>{$item->published|date_format:"%standard"}</pubDate>
    </item>
{/foreach}
{/if}
{include file='part.rss.footer'}


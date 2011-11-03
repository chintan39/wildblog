{foreach from=$rssFeeds item=rssFeed}
<link rel="alternate" type="application/rss+xml" title="{$rssFeed.name}" href="{$rssFeed.link}" />
{/foreach}


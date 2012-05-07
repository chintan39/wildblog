<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>{tp}RSS title{/tp}</title>
    <link>{$base}</link>
    <description>{tp}RSS description{/tp}</description>
    <language>cs</language>
    <pubDate>{$rssInfo.publishDate|date_format2:"%standard"}</pubDate>
    <generator>Wild-Web</generator>
    <webMaster>horak.jan@centrum.cz (Jan Horak)</webMaster>
    <ttl>60</ttl>
    <atom:link href="{$rssInfo.link}" rel="self" type="application/rss+xml" />

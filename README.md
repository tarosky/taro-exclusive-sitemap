# taro-exclusive-sitemap

Tags: series, posts, news  
Contributors: tarosky, Takahashi_Fumiki  
Tested up to: 6.5  
Requires at least: 5.9  
Requires PHP: 7.4  
Stable Tag: nightly  
License: GPLv3 or later  
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

A WordPress plugin to generate single sitemap for an exclusive sitemap.

## Description

This plugin add an **Exclusive Sitemap" to your WordPress site.

### What is an "exclusive sitemap"?

Search engine robots, such as those used by Google, crawl websites by following links to gather data for their search index. A sitemap serves as a signal to these robots to expedite the crawling process.

A typical sitemap provides a list of URLs that you want indexed, while an exclusive sitemap lists URLs where changes need to be reflected. This includes:

- Pages that you want to quickly set to noindex
- URLs with query parameters that have been indexed unintentionally

When displaying URLs indicated by the exclusive sitemap, ensure the following correct actions are taken:

- If the page should not be indexed, it should have a noindex directive in `<head>` section.
- The page should include a `<link rel="canonical" />` meta tag pointing to the correct URL (standard in WordPress)


### Examples

Here is an example of how to use the exclusive sitemap based on a real scenario.

You open Google Search Console and notice that duplicate URLs are being indexed.

1. `https://example.com/category/?suponcered=true` - This URL was linked from an old advertisement, but the parameter is unnecessary.
2. `https://example.com/article/1234/amp/` - You no longer use [AMP](https://amp.dev/), but this URL is still indexed!
3. `https://example.com/article/5678/?snscid=1234567890` - You have no idea about this, but after investigation, you find that the `snscid` query parameter is added when the URL is shared on a specific social media platform.

Let's remove these URLs from Google's search index.

- Enter the URLs in the WordPress admin panel under Tools > Exclusionary Sitemap.
- After saving, submit the sitemap URL generated on the same screen to Google Search Console or other relevant tools.
- Wait for Google to crawl the site.
- Once you confirm that the URLs are no longer indexed, delete the URLs from WordPress.


## Installation

### From Plugin Repository

Click install and activate it.

### From Github

See [releases](https://github.com/tarosky/taro-exclusive-sitemap/releases).

## FAQ

### Where can I get supported?

Please create new ticket on support forum.

### How can I contribute?

Create a new [issue](https://github.com/tarosky/taro-exclusive-sitemap/issues) or send [pull requests](https://github.com/tarosky/taro-exclusive-sitemap/pulls).

## Changelog

### 1.0.0

* First release.

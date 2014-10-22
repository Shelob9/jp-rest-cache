JP REST API CACHE
=====================

Soft-expiring, server-side cache  for the WordPress REST API (WP REST).

Utilizes [Mark Jaquith's WP-TLC-Transients Library](https://github.com/markjaquith/WP-TLC-Transients). Requires WordPress and the [WordPress REST API](http://wp-api.org).

Keep in mind two things when using please:
1) Caching does not categorically equal faster. Do some science, test on your testing server, not your live server for the love of Gaia, to figure out if this actually improves performance.
2) You're probably better off using a persistent object cache on your site. Again, do science, figure out what works best. My feelings will not be hurt if you use something else instead.

BTW By "do science" I mean do a set of repeatable tests with a one independent variable (cache method) and keep everything else the same. Compare results and see if you're hypothesis (cache method == faster) evaluates true or false.

### Installation
This is not a plugin. It's a composer library. Putting this in your plugin directory and then opening an issue when this doesn't show up in your plugin admin is a violation of intergalactic law.

Add `"shelob9/jp-wp-rest-api-client": "dev-master"` to your site/plugin/theme's composer.json. 


### License
Copyright 2014 Josh Pollock. Licensed under the terms of the GNU General public license version 2. Please share with your neighbor.

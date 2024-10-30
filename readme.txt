=== Internal Comments ===
Contributors: ahegyes, deepwebsolutions
Tags: internal comments, private comments
Requires at least: 5.5
Tested up to: 6.0
Requires PHP: 7.4
Stable tag: 1.2.4  
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A WordPress plugin for leaving admin-facing comments that are only visible to administrators and other users with admin-area access on to post private admin-side comments to any registered post type.

== Description ==

**Internal Comments is a plugin that lets you post admin-facing comments that are only visible to users with admin-area access on any registered post type.**

After selecting the post types that support internal comments in the plugin's settings, a new metabox will appear on the edit screen of posts belonging to those post types. Users with appropriate permissions will now be able to post new internal comments.

Here is a short overview of the features offered by this plugin:

* Internal comments can be enabled on all registered post types for which WordPress auto-generates the UI screens.
* Administrators can view a list of all internal comments posted on the site through a new submenu point under the *Comments* admin menu.
* The number of internal comments a post has can be viewed in a new column on the WordPress posts list table screen.
* **[Premium]** Internal comments can be quick-viewed from the WordPress posts list column without opening the single post edit screen.
* **[Premium]** Internal comments can be marked as private so that they are only visible to the original author and administrators.
* **[Premium]** Internal comments can be edited and replied to inline.

### Premium support and features

Some of the features mentioned above are only bundled with the premium version of our plugin available [here](https://www.deep-web-solutions.com/plugins/internal-comments/). It is perfectly possible, however, to use the free version and extend it via filters and actions with your own version of the same premium features.

Premium customers are also entitled to prioritized help and support through [our support forum](https://www.deep-web-solutions.com/support/).

== Installation ==

There are no prerequisites other than the minimum WordPress version.

### INSTALL FROM WITHIN WORDPRESS

1. Visit the plugins page withing your dashboard and select `Add New`.
1. Search for `Internal Comments` and click the `Install Now` button.

1. Activate the plugin from within your `Plugins` page.


### INSTALL MANUALLY

1. Download the plugin from https://wordpress.org/plugins/internal-comments and unzip the archive.
1. Upload the `internal-comments` folder to the `/wp-content/plugins/` directory.

1. Activate the plugin through the `Plugins` menu in WordPress.


### AFTER ACTIVATION

If the minimum WordPress and PHP requirements are met, you will find a menu submenu "Internal Comments" under the "Settings" admin menu. There you will be able to:

1. Choose which post types support internal comments.
1. Choose which other features of the plugin should be enabled, if applicable.

== Frequently Asked Questions ==

= Do internal comments show up on the front-end of the site? =

Internal comments do **not** show up on the front-end nor are they included in the post's comment count. If your theme somehow bypasses our filters and displays them, please reach out to us so we can try to offer compatibility with your theme.

= Can I post sensitive information in internal comments? =

Internal comments are meant strictly for internal use but you should still *avoid* storing sensitive information. The data is not encrypted in the database and we can't promise that it won't leak. Think of these comments more as a digital version of post-its.

= Is this compatible with WooCommerce? =

Yes! You can use internal comments on WooCommerce orders, products, and even coupons.

= Is this compatible with [insert plugin name]? =

Likely yes. Internal Comments works with all registered post types that use the WordPress UI. If you don't find your post type in the plugin's settings, please raise a support question.

= How can I get help if I'm stuck? =

If you're using the free version, you can find more examples in [our knowledge base](https://docs.deep-web-solutions.com/article-categories/internal-comments/) and you can open a community support ticket here at [wordpress.org](https://wordpress.org/support/plugin/internal-comments/). Our staff regularly goes through the community forum to try and help.

If you've purchased the premium version of the plugin [on our website](https://www.deep-web-solutions.com/plugins/internal-comments/), you are entitled to a year of premium updates and access to [our prioritized support forum](https://www.deep-web-solutions.com/support/). You can use that to contact our support team directly if you have any questions.


= I have a question that is not listed here =

There is a chance that your question might be answered [in our knowledge base](https://docs.deep-web-solutions.com/article-categories/internal-comments/). Otherwise, feel free to reach out via our [contact form](https://www.deep-web-solutions.com/contact/).


== Screenshots ==

1. Plugin settings page.
2. Example of how the metabox looks like in Gutenberg.
3. Example of how the metabox looks like in the Classic Editor.
4. Example of the WP tables column.

== Changelog ==

= 1.2.4 (July 17th, 2022) =
* Tested up to WordPress 6.0.1.
* Updated Freemius SDK.

= 1.2.3 (March 8th, 2022) =
* Fix: Notice on inline-save AJAX calls.
* Security: updated Freemius SDK.
* Dev: updated DWS framework.

= 1.2.2 (February 8th, 2022) =
* Tested up to WordPress 5.9.
* Dev: updated DWS framework.

= 1.2.1 (January 14th, 2022) =
* Tested up to PHP 8.1.
* Dev: updated DWS framework.

= 1.2.0 (December 10th, 2021) =
* Fixed readme.
* Dev: moved most HTML to dedicated files.
* Dev: updated DWS framework.
* Dev: added more automated tests.

= 1.1.2 (September 27th, 2021) =
* Fixed metabox being outputted on all post types when no post types have been selected on the settings page.
* Fixed readme formatting for WordPress.org.

= 1.1.0, 1.1.1 (September 26th, 2021) =
* Fixed fatal error on cron-like requests.
* Tested with the latest version of WordPress.
* Scripts and styles are only enqueued on relevant admin pages.

* Fixed language domain inside dependencies folder.
* Dev: rebuilt the entire settings page. Please re-save your settings after updating.
* Dev: updated DWS framework.

= 1.0.0 (July 27th, 2021) =
* First official release.

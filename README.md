# qnrwp_a

Bare-bones Wordpress theme for web developers, intended as a starting point for custom themes for clients.

The theme incorporates Quicknr Interface widgets as custom WP Widgets and Shortcodes. Note that Quicknr Interface files are not included. To use QI, create a "res" folder in the theme folder, and place the QI "css", "font" and "js" folders in it.

This theme will be developed rapidly. No releases will be packaged - the latest commit is the latest version. Once you have adapted this theme to your requirements, you will no longer want to refer to this original, an update would not make sense. This theme is expected to be customized beyond what would usually be done with a child theme.

Aspects of this theme:

* No effort made to translate the output, English only.
* File number kept to a minimum - all Posts, Pages, archives and search results handled by index.php. No page templates used.
* Dashboard is not cluttered with theme settings. All custom widgets are defined in Pages and subpages or implemented with shortcodes.
* Semi-automated layout, no need for page building plugins. Presence of sidebar widgets controls the layout logically.

More details are at the sample site for the theme: http://quicknr.fast-page.org/wp/ .
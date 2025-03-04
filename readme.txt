=== Plugin Name ===
Contributors: Cryout Creations
Donate link: https://www.cryoutcreations.eu/donate/
Tags: theme, admin
Requires at least: 4.5
Tested up to: 6.6
Stable tag: 0.5.16
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

This plugin is designed to inter-operate with our Mantra, Parabola, Tempera, Nirvana themes to enable their settings pages.

== Description ==

This plugin is designed to inter-operate with our [Nirvana](https://wordpress.org/themes/nirvana/), [Tempera](https://wordpress.org/themes/tempera/), [Parabola](https://wordpress.org/themes/parabola/) and [Mantra](https://wordpress.org/themes/mantra/) themes and enable their classic settings pages.

= Compatibility = 
The plugin is intended to be used with the following theme releases:

* Nirvana version 1.2 and newer
* Tempera version 1.4 and newer
* Parabola version 1.6 and newer
* Mantra version 2.5 and newer

This plugin has no use and will do nothing if you do not use any of the listed themes.

== Installation ==

= Automatic installation =

0. Have one of our supported themes activated with a missing settings page.
1. Navigate to Plugins in your dashboard and click the Add New button.
2. Type in "Cryout Theme Settings" in the search box on the right and press Enter, then click the Install button next to the plugin title. 
3. After installation Activate the plugin, then navigate to Appearance > [Theme] Settings to access the restored theme settings page.

= Manual installation =

0. Have one of our supported themes activated with a missing settings page.
1. Upload `cryout-theme-settings` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Appearance > [Theme] Settings to access the restored theme settings page. 
4. Additionally, check the plugin's status on the Appearance > Serious Theme Settings page. 

== Changelog ==

= 0.5.16 =
* Reorganized plugin folders and files
* Tested for compatibility with PHP 8.3
* Bumped supported WordPress version to 6.6

= 0.5.15 =
* Fixed 'jQuery.fn.click()' deprecation warning on themes' settings pages
* Reorganized setting page styling and scripting
* Tested for compatibility with PHP 8.0, 8.1 and 8.2
* Tested supported WordPress version to 6.5

= 0.5.14 =
* Updated for compatibility with the changes in Tempera 1.8.0
* Bumped supported WordPress version to 5.9

= 0.5.13 =
* Updated for compatibility with the changes in Nirvana 1.6.0
* Bumped supported WordPress version to 5.8

= 0.5.12 =
* Updated for compatibility with the changes in Parabola 2.4.0
* Improved version detection for better compatibility with customized themes
* Bumped supported WordPress version to 5.7

= 0.5.11 = 
* Updated for compatibility with the changes in Mantra 3.3.0
* Some code cleanup and optimization
* Bumped supported WordPress version to 5.5

= 0.5.10 =
* Updated themes information
* Bumped supported WordPress version to 5.2

= 0.5.9 =
* Extended support for Mantra 3.0
* Fixed missing "need help" image on some browsers due to typo
* Added direct access check to all php files
* Updated themes information
* Bumped supported WordPress version to 4.9.1

= 0.5.8.1 = 
* Fixed compatibility mode malfunctioning
* Fixed missing theme images in compatibility mode info

= 0.5.8 =
* Added meta links
* Updated plugin's about page
* Added 'featured themes' and 'priority support' panels

= 0.5.7 =
* Fixed incorrect status for Nirvana versions before 1.2

= 0.5.6 =
* Added support for Mantra 2.5

= 0.5.5 = 
* Added support for Parabola 1.6
* Added detection of supported themes when the theme folder is renamed

= 0.5.4 = 
* Fixed compatibility support for Mantra

= 0.5.3 = 
* Added support for Tempera 1.4
* Fixed typo causing error in compatibility code

= 0.5.2 = 
* Added themes compatibility fix for WordPress 4.4-RC1 and newer

= 0.5.1 = 
* Fixed detection of parent theme name and version when using a child theme
* Clarified plugin information

= 0.5 =
* Initial release. Currently Nirvana 1.2 implements support for this plugin.

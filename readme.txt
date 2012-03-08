=== Plugin Name ===
Contributors: juliobox
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KJGT942XKWJ6W
Tags: role, member, user, moderator, comment, comments, capability
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: trunk

Add a real Comments Moderator Role to your powerful WordPress Blog!

== Description ==

Just install the plugin, activate it: You can now change a user's role to "Moderator", he can now moderate comments (all or only his), and only do this!

== Installation ==

1. Upload the *"baw-moderator-role"* folder into the *"/wp-content/plugins/"* directory
1. Activate the plugin through the *"Plugins"* menu in WordPress
1. You can now change a user's role to "Moderator", he can now moderate comments (all or only his see FAQ), and only do this!
1. See FAQ for filters/actions usage

== Frequently Asked Questions ==

= I just want my moderators can moderate their own comments, is this possible? =

Yes, you can play with my filter named "allow_moderate_all_comments", example of use (add this in your functions.php from theme folder):
1. `add_filter( 'allow_moderate_all_comments', '__return_false' ); // This will remove the "all comments" moderation`
1. or we can check a user meta to allow one user to be kind of "admin moderator", example with a moderator user, with ID 123
`function check_moderator()
{
	return $GLOBALS['current_user']->ID == 123;
}
add_filter( 'allow_moderate_all_comments', 'check_moderator' );`

= I use a plugin that adds a page for comment stuff, this page is not here, you're mean! =

No worry, i've think about it!
Use the filter "allowed_moderator_menus" and add your plugins pages. Example of use:
`function add_my_plugin_page_for_moderator_menu_this_function_got_a_very_long_name_damn_it( $pages )
{
	$pages[] = __( 'My plugin page menu name', 'plugin_context' );
}
add_action( 'allowed_moderator_menus', 'add_my_plugin_page_for_moderator_menu_this_function_got_a_very_long_name_damn_it' );`

= Ok, now i can see a plugin menu in my admin bar and i don't want it!! =

Same stuff! use this filter "baw_before_admin_bar_render", example:
`function remove_my_plugin_in_admin_bar( &$wp_admin_bar )
{
	$wp_admin_bar->remove_menu('my-bad-plugin');
}
add_action( 'baw_before_admin_bar_render', 'remove_my_plugin_in_admin_bar' );`

== Screenshots ==

1. The new little menu

== Changelog ==

= 1.2 =
* 07 mar 2012
* Bug fix when updating a comment with ajax (quick edit)
* Add a new hook (See FAQ)
* Readme improved (so, read him)

= 1.1 =
* 07 mar 2012
* First release


== Upgrade Notice ==

None
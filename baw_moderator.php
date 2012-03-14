<?php
/*
Plugin Name: BAW Moderator Role
Plugin URI: http://www.boiteaweb.fr/modorole
Description: Creates a new user "Moderator" role who can moderate comments only
Version: 1.3
Author: Juliobox
Author URI: http://www.boiteaweb.fr
*/

function bawmro_redirect_users()
{
	global $pagenow;
	// Pages forbidden by the plugin but autorized by WordPress
	$pagesnow = array( 'index.php', 'post-new.php', 'edit.php', 'edit-tags.php', 'tools.php' );
	// If the user id a Moderator, if not, let's continue
	if ( current_user_can( 'moderator' ) && $pagenow ) {
		global $menu; 
		if( $menu ) {
			// Keep only these 2 menus, a filter is used if a plugin adds its own menu relating to comments.
			$menu_ok = apply_filters( 'allowed_moderator_menus', array( __('Comments'), __('Profile') ) );
			foreach( $menu as $menu_key => $menu_val ) {
				$menu_value = explode( ' ',$menu[$menu_key][0] );
				if( !in_array( $menu_value[0] != NULL ? $menu_value[0] : '', $menu_ok ) !== false ) {
					// Delete all others menus entries
					unset( $menu[$menu_key] );
				}
			}
		}
		// Is the user is trying to access to a forbidden page, redirect him on his job : moderate comment !
		if ( in_array( $pagenow, $pagesnow ) )
			wp_redirect( admin_url( 'edit-comments.php' ) );
	}
}
add_action( 'admin_init', 'bawmro_redirect_users' );

function bawmro_add_role() 
{
	// The new role.
	add_role(
		'moderator',
		_x('Moderator', 'User role'), // translators: user role
		array(
			'read' => true,
			'edit_posts' => true,
			'edit_other_posts' => true,
			'edit_published_posts' => true,
			'moderate_comments' => true
		)
	);
}
add_action( 'admin_init', 'bawmro_add_role' );

function bawmro_edit_admin_bar()
{	
	global $wp_admin_bar;
	if ( current_user_can( 'moderator' ) )
		// If the user is Moderator, remove the "New post" menu in admin bar
		$wp_admin_bar->remove_menu('new-content');
		// This filter is used to add more admin bar menu deletion
		do_action( 'baw_before_admin_bar_render', &$wp_admin_bar );
}
add_action( 'wp_before_admin_bar_render', 'bawmro_edit_admin_bar' );

function bawmro_map_meta_cap( $caps, $cap, $user_id, $args )
{
	// Force comments to be autorized for moderation for "moderator" role
	if( apply_filters( 'allow_moderate_all_comments', true ) && in_array( $cap, array( 'edit_comment', 'edit_post' ) ) && $caps && current_user_can( 'moderator' ) )
		return null;
	return $caps;
}
add_filter('map_meta_cap', 'bawmro_map_meta_cap', 10, 4 );

function bawmro_deactivation()
{
	$users = get_users( array( 'role' => 'moderator' ) );
	// If at least 1 user got the Moderator role, do not deactivate the plugin, you have to change all Moderators role before.
	if ( !count( $users ) ) {
		remove_role( 'moderator' );
	}else{
		// Light L10N
		if( get_locale() != 'fr_FR' ) {
			$msg = 'You have to remove the Moderator role from all users before deactivate/uninstall the plugin.';
		}else{
			$msg = 'Vous devez supprimer le role Moderator de tous les utilisateurs avant de d&eacute;sactiver/supprimer le plugin.';
		}
		wp_die( $msg );
	}
}
register_deactivation_hook( __FILE__, 'bawmro_deactivation' );

?>
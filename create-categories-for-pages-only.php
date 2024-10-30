<?php
/*
Plugin Name: Create Categories For Pages Only
Plugin URI: https://www.abcampus.com
Description: This plugin will add categories that are specific to your pages. They are NOT shared with post categories. It will also display the page categories on the Wordpress dashboard, allowing you to sort pages by category.
Version: 1.0
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

## add categories that are specific to pages, i.e. not shared with posts categories

  add_action( 'init', 'ccfpo_pages_tax' );
function ccfpo_pages_tax() {
 register_taxonomy(
  'pagecategories',
  'page',
  array(
    'label' => 'Page categories',
    'labels' => array(
    'name' => 'Page categories',
    'singular_name' => 'Page category',
    'all_items' => 'All page categories',
    'edit_item' => 'Édit page category',
    'view_item' => 'View page category',
    'update_item' => 'Update page category',
    'add_new_item' => 'Add page category',
    'new_item_name' => 'New page category',
    'search_items' => 'Search page categories',
  ),
  	'hierarchical' => true,
  	'public' => true,
  	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'show_in_nav_menus' => false,
	'show_tagcloud' => false,
	'show_in_quick_edit' => true,
	'show_admin_column' => true,
	'show_in_rest' => true,
  
  )
);
}
register_taxonomy_for_object_type( 'pagecategories', 'page' );

## allow filtering of pages by taxonomy on the dashboard

function ccfpo_filter_pages_by_taxonomies( $post_type, $which ) {

	// Apply this only on a specific post type
	if ( 'page' !== $post_type )
		return;

	// A list of taxonomy slugs to filter by
	$taxonomies = array( 'pagecategories' );

	foreach ( $taxonomies as $taxonomy_slug ) {

		// Retrieve taxonomy data
		$taxonomy_obj = get_taxonomy( $taxonomy_slug );
		$taxonomy_name = $taxonomy_obj->labels->name;

		// Retrieve taxonomy terms
		$terms = get_terms( $taxonomy_slug );

		// Display filter HTML
		echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
		echo '<option value="">' . sprintf( esc_html__( 'Show All %s', 'text_domain' ), $taxonomy_name ) . '</option>';
		foreach ( $terms as $term ) {
			printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				$term->slug,
				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
				$term->name,
				$term->count
			);
		}
		echo '</select>'; 
	}

}
add_action( 'restrict_manage_posts', 'ccfpo_filter_pages_by_taxonomies' , 10, 2);

?>

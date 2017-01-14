<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ( !post_type_exists( 'eazy-photo' ) ) {
	// registers photo post type
	add_action( 'init', 'eazy_photography_post_type', 0 );
	function eazy_photography_post_type() {
		$labels = array(
			'name'=>'Photos',
			'singular_name'=>'Photo',
			'add_new'=>'Add photo',
			'add_new_item'=>'Create photo',
			'edit_item'=>'Edit photo',
			'new_item'=>'New photo',
			'view_item'=>'View photo',
			'search_items'=>'Search photos',
		);
		$args = array(
			'labels'=>$labels, 
			'public'=>true,
			'has_archive' => true,
			'menu_position'=>31,
			'rewrite' => array( 'slug' => 'photos'),
			'supports'=>array('title','editor','excerpt','thumbnail','custom-fields', 'comments', 'author'),
			'query_var' => true,
			'show_in_nav_menus' => true,
			'menu_icon'   => 'dashicons-camera',
			'capability_type' => 'post'
			);	
		register_post_type('eazy-photo',$args);
	}


	// registers photo category and collection taxonomies
	add_action( 'init', 'eazy_photography_taxonomy', 0 );
	function eazy_photography_taxonomy() {
		$photo_cat_labels = array(
			'name' => 'Photos Categories',
			'singular_name' => 'Photo Categories',
			'search_items' =>  'Search Photo Categories',
			'popular_items' => 'Popular Photo Categories',
			'all_items' => 'All Photo Categories',
			'parent_item' => 'Parent Photo Category',
			'parent_item_colon' => 'Parent Photo Category:',
			'edit_item' => 'Edit Photo Category', 
			'update_item' => 'Update Photo Category',
			'add_new_item' => 'Add New Photo Category',
			'new_item_name' => 'New Photo Category Name',
			'separate_items_with_commas' => 'Separate photo categories with commas',
			'add_or_remove_items' => 'Add or remove photo category',
			'choose_from_most_used' => 'Choose from the most used photo categories',
			'menu_name' => 'Categories'
		);
		$photo_collection_labels = array(
			'name' => 'Photos Collections',
			'singular_name' => 'Photo Collections',
			'search_items' =>  'Search Photo Collections',
			'popular_items' => 'Popular Photo Collections',
			'all_items' => 'All Photo Collections',
			'parent_item' => 'Parent Photo Collection',
			'parent_item_colon' => 'Parent Photo Collection:',
			'edit_item' => 'Edit Photo Collection', 
			'update_item' => 'Update Photo Collection',
			'add_new_item' => 'Add New Photo Collection',
			'new_item_name' => 'New Photo Collection Name',
			'separate_items_with_commas' => 'Separate photo collections with commas',
			'add_or_remove_items' => 'Add or remove photo collection',
			'choose_from_most_used' => 'Choose from the most used photo collections',
			'menu_name' => 'Collections'
		);
		register_taxonomy('photo-category',
			array('eazy-photo'), 
				array(
					'hierarchical'=>true,
					'show_ui'=>true,
					'labels' => $photo_cat_labels,
					'public' => true,
					'query_var'=> true,
					'label' => 'Photo Categories',
					'rewrite' => array('slug' => 'photo-category', 'hierarchical' => true)
					)
		);
		register_taxonomy('photo-collection',
			array('eazy-photo'), 
				array(
					'hierarchical'=>false,
					'show_ui'=>true,
					'labels' => $photo_collection_labels,
					'public' => true,
					'query_var'=> true,
					'label' => 'Photo Collections',
					'rewrite' => array('slug' => 'photo-collection', 'hierarchical' => true)
					)
		);
	}

	//flushes rewrite rules so photos show up in front end 
	add_action('init', 'flush_rewrite_rules', 10 );
}
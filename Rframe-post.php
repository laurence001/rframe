<?php 
function rframe_cpt() {

	register_post_type( 'rframe',
     array(
        'labels' => array(
		'name'                  => __( 'Rframe', 'rframe' ),
		'singular_name'         => __( 'Rframe', 'rframe' ),
		'menu_name'             => __( 'Rframe', 'rframe' ),
		'name_admin_bar'        => __( 'Rframe', 'rframe' ),
		'archives'              => __( 'Rframe Archives', 'rframe' ),
		'attributes'            => __( 'Rframe Attributes', 'rframe' ),
		'parent_item_colon'     => __( 'Parent Rframe:', 'rframe' ),
		'all_items'             => __( 'All Rframes', 'rframe' ),
		'add_new_item'          => __( 'Add New Rframe', 'rframe' ),
		'add_new'               => __( 'Add New', 'rframe' ),
		'new_item'              => __( 'New Rframe', 'rframe' ),
		'edit_item'             => __( 'Edit Rframe', 'rframe' ),
		'update_item'           => __( 'Update Rframe', 'rframe' ),
		'view_item'             => __( 'View Rframe', 'rframe' ),
		'view_items'            => __( 'View Rframes', 'rframe' ),
		'search_items'          => __( 'Search Rframe', 'rframe' ),
		'not_found'             => __( 'Not found', 'rframe' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'rframe' ),
		'featured_image'        => __( 'Featured Image', 'rframe' ),
		'set_featured_image'    => __( 'Set featured image', 'rframe' ),
		'remove_featured_image' => __( 'Remove featured image', 'rframe' ),
		'use_featured_image'    => __( 'Use as featured image', 'rframe' ),
		'insert_into_item'      => __( 'Insert into item', 'rframe' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Rframe', 'rframe' ),
		'items_list'            => __( 'Rframes list', 'rframe' ),
		'items_list_navigation' => __( 'Rframes list navigation', 'rframe' ),
		'filter_items_list'     => __( 'Filter Rframes list', 'rframe' ),
	),
			'has_archive'         	=> true,
			'public'              	=> true,
			'publicly_queryable'  	=> true,
			'exclude_from_search' 	=> true,
			'show_in_rest'		 	=> true,
			'show_ui'             	=> true,
			'show_in_menu'        	=> true,
			'show_admin_column'     => true,
			'menu_position'       	=> 80,
			'menu_icon'				=> 'dashicons-media-code',
			'capability_type'     	=> 'post',
			'hierarchical'        	=> true,
			'supports'            	=> array( 'title', 'revisions' ),
			'has_archive'         	=> true,
			'rewrite' 				=> array('slug' => 'rframe', 'with_front' => false),
			'query_var'           	=> true,
			'can_export'          	=> true
	 )
  );
}
add_action( 'init', 'rframe_cpt');


//Flush
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'rframe_flush_rewrites' );
function rframe_flush_rewrites() {
	rframe_create_post_type();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

//Preview (debug)

add_filter('_wp_post_revision_fields', 'add_rframe_debug_preview');
function add_rframe_debug_preview($fields){
   $fields["debug_preview"] = "debug_preview";
   return $fields;
}

add_action( 'edit_form_after_title', 'rframe_input_debug_preview' );
function rframe_input_debug_preview() {
   echo '<input type="hidden" name="debug_preview" value="debug_preview">';
}

function html_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function html_add_meta_box() {
	add_meta_box(
		'html-html',
		__( 'Rframe', 'rframe' ),
		'html_html',
		'rframe',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'html_add_meta_box' );

//Shortcode
add_shortcode('rframe', 'ag_iframe');
function ag_iframe($atts, $content) {
	if (!$atts['width']) { $atts['width'] = 630; }
	if (!$atts['height']) { $atts['height'] = 1500; }
	return '<iframe border="0" class="shortcode_iframe" src="' . $atts['src'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '"></iframe>';
}

//Display on admin
	function html_html( $post) {
	wp_nonce_field( '_html_nonce', 'html_nonce' ); ?>
	
	<h3><?php _e('1. Add the post title and copy-paste the whole HTML code generated by R', 'rframe'); ?></h3>
	
	<p>
		<textarea name="html_rframe" style="min-height:220px!important;" class="widefat" id="html_rframe" ><?php echo html_get_meta( 'html_rframe' ); ?></textarea>
	</p>
	<?php $iframe = html_get_meta( 'html_rframe' ); 
	
	if (!empty($iframe)) : ?>
		
		<h3><?php _e('2. Publish your post', 'rframe'); ?></h3>
		
		<h3><?php _e('3. Add the following shortcode within your post or page (copy-paste)', 'rframe'); ?></h3>
		
		<?php $link = get_permalink(); ?>
		
		<div style="background:#F7F7F8;padding:10px;">
			<p>[rframe src="<?php echo $link; ?>" width="100%" height="550px"]</p>
		</div>
		<p>
			<em><?php _e( 'You can modify the size of the iframe by replacing the width and the height values. You can also use a WP plugin to embed your iframe more easily.', 'rframe' )?></em>
		</p>
		
		<hr/>
		
		<h4><strong><?php _e( 'Here\'s the full rframe code', 'rframe' )?></strong></h4> 	
		
		<input type="text" class="widefat" value='&lt;iframe src="<?php echo $link; ?>" width="100%" height="550px"&gt;&lt;/iframe&gt;' />
		
		<h3><?php _e('Preview', 'rframe'); ?></h3>
		
		<iframe src="<?php echo $link; ?>" width="100%" height="550px"> </iframe>
		
		
	<?php 
	endif;
}

function html_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['html_nonce'] ) || ! wp_verify_nonce( $_POST['html_nonce'], '_html_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['html_rframe'] ) )
		update_post_meta( $post_id, 'html_rframe', htmlentities($_POST[ 'html_rframe' ])  );
	}
add_action( 'save_post', 'html_save' );

//Template
function template_rframe($template)
	{
		global $wp_query;
		$post_type = get_query_var('post_type');
			if($post_type == 'rframe' )
			{							
				$template = plugin_dir_path( __FILE__ ) . 'Rframe-template.php';
			}
		return $template;
	}
	
add_filter('template_include', 'template_rframe');

   add_action('pre_get_posts', 'filter_posts_list'); 

    function filter_posts_list($query)  {
        //$pagenow holds the name of the current page being viewed
         global $pagenow, $typenow;  
        if(current_user_can('edit_posts') && ('edit.php' == $pagenow))  { 
            //global $query's set() method for setting
            $query->set('orderby', 'date');
            $query->set('order', 'desc');
        }
    }
?>

<?php

class Gohar_e_Hikmat_Questions{
    private static $instance = null;
    public function __construct()    
    {
        add_action( 'init', array($this,'codex_question_init') );
        add_action( 'admin_menu', array($this,'rudr_add_a_metabox') );
        add_action( 'save_post', array($this,'rudr_save_post_meta'), 10, 2 );
        add_action('post_edit_form_tag', array($this,'add_post_enctype'));
    }

    function add_post_enctype() {
        echo ' enctype="multipart/form-data"';
    }

    /**
     * Register a question post type.
     *
     * @link http://codex.wordpress.org/Function_Reference/register_post_type
     */
    function codex_question_init() {
        $labels = array(
            'name'               => _x( 'Questions', 'post type general name', 'your-plugin-textdomain' ),
            'singular_name'      => _x( 'Question', 'post type singular name', 'your-plugin-textdomain' ),
            'menu_name'          => _x( 'Questions', 'admin menu', 'your-plugin-textdomain' ),
            'name_admin_bar'     => _x( 'Question', 'add new on admin bar', 'your-plugin-textdomain' ),
            'add_new'            => _x( 'Add New', 'question', 'your-plugin-textdomain' ),
            'add_new_item'       => __( 'Add New question', 'your-plugin-textdomain' ),
            'new_item'           => __( 'New question', 'your-plugin-textdomain' ),
            'edit_item'          => __( 'Edit question', 'your-plugin-textdomain' ),
            'view_item'          => __( 'View question', 'your-plugin-textdomain' ),
            'all_items'          => __( 'All questions', 'your-plugin-textdomain' ),
            'search_items'       => __( 'Search questions', 'your-plugin-textdomain' ),
            'parent_item_colon'  => __( 'Parent questions:', 'your-plugin-textdomain' ),
            'not_found'          => __( 'No questions found.', 'your-plugin-textdomain' ),
            'not_found_in_trash' => __( 'No questions found in Trash.', 'your-plugin-textdomain' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'your-plugin-textdomain' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'question' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'question', $args );
    }

    function rudr_add_a_metabox() {
        add_meta_box(
            'gohar_e_hikmat_question_box', // metabox ID, it also will be the HTML id attribute
            'Questions', // title
            array($this,'question_display_metabox'), // this is a callback function, which will print HTML of our metabox
            'question', // post type or post types in array
            'normal', // position on the screen where metabox should be displayed (normal, side, advanced)
            'default' // priority over another metaboxes on this page (default, low, high, core)
        );
    }
    function rudr_save_post_meta( $post_id, $post ) {
        /* 
        * Security checks
        */
        
        if ( !isset( $_POST['rudr_metabox_nonce'] ) 
        || !wp_verify_nonce( $_POST['rudr_metabox_nonce'], 'gh_question_save' ) )
        return $post_id;
        /* 
        * Check current user permissions
        */
       
        $post_type = get_post_type_object( $post->post_type );
        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;
        /*
        * Do not save the data if autosave
        */
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;
        
        if ($post->post_type == 'question') { // define your own post type here
            
            update_post_meta($post_id, 'gohar_e_hikmat_questions',  $_POST['gh_question']  );
            
            if( ! empty( $_FILES ) && isset( $_FILES['gh_pdf'] ) ) {
                // Upload the goal image to the uploads directory, resize the image, then upload the resized version
                // print_r($_FILES['gh_pdf'] );
                // var_dump(@file_get_contents( $_FILES['gh_pdf']['tmp_name']));
                // var_dump(! empty( $_FILES ) && isset( $_FILES['gh_pdf'] ));
                // var_dump($post->post_type );die();
				$goal_image_file = wp_upload_bits( $_FILES['gh_pdf']['name'], null, @file_get_contents( $_FILES['gh_pdf']['tmp_name'] ) );

				if( false == $goal_image_file['error'] ) {
				
					// Since we've already added the key for this, we'll just update it with the file.
					update_post_meta( $post_id, 'gohar_e_hikmat_pdf', $goal_image_file['url'] );
		
				} // end if/else
			} // end if/else
				//
        }
       
        return $post_id;
    }
    
    function question_display_metabox( $post ) {
       

        $template_name = 'question_metabox';
        ob_start();
    
        do_action( 'personalize_login_before_' . $template_name );
    
        require( 'templates/' . $template_name . '.php');
    
        do_action( 'personalize_login_after_' . $template_name );
    
        $html = ob_get_contents();
        ob_end_clean();
    
        echo $html;
        
    }
    

    public static function getInstance()
    {
      if(!self::$instance)
      {
        self::$instance = new Gohar_e_Hikmat_Questions();
      }
     
      return self::$instance;
    }
}

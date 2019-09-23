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
            'name'               => _x( 'Topic', 'post type general name', 'your-plugin-textdomain' ),
            'singular_name'      => _x( 'Topic', 'post type singular name', 'your-plugin-textdomain' ),
            'menu_name'          => _x( 'Gohar e Hikmat', 'admin menu', 'your-plugin-textdomain' ),
            'name_admin_bar'     => _x( 'Gohar e Hikmat', 'add new on admin bar', 'your-plugin-textdomain' ),
            'add_new'            => _x( 'Add New', 'gohar_e_hikmat', 'your-plugin-textdomain' ),
            'add_new_item'       => __( 'Add New Topic', 'your-plugin-textdomain' ),
            'new_item'           => __( 'New Topic', 'your-plugin-textdomain' ),
            'edit_item'          => __( 'Edit Topic', 'your-plugin-textdomain' ),
            'view_item'          => __( 'View Topic', 'your-plugin-textdomain' ),
            'all_items'          => __( 'All Topic', 'your-plugin-textdomain' ),
            'search_items'       => __( 'Search Topic', 'your-plugin-textdomain' ),
            'parent_item_colon'  => __( 'Parent Topic:', 'your-plugin-textdomain' ),
            'not_found'          => __( 'No Topic found.', 'your-plugin-textdomain' ),
            'not_found_in_trash' => __( 'No Topic found in Trash.', 'your-plugin-textdomain' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'your-plugin-textdomain' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'gohar_e_hikmat' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'gohar_e_hikmat', $args );
    }

    function rudr_add_a_metabox() {
        add_meta_box(
            'gohar_e_hikmat_question_box', // metabox ID, it also will be the HTML id attribute
            'Questions', // title
            array($this,'question_display_metabox'), // this is a callback function, which will print HTML of our metabox
            'gohar_e_hikmat', // post type or post types in array
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
        
        if ($post->post_type == 'gohar_e_hikmat') { // define your own post type here
            
            $_tmp_gh_question = $_POST['gh_question'];

            foreach($_POST['gh_question'] as $ind => $question)
            {
                $_tmp_gh_question[$ind]['correct_answer'] = ['answer' => $question['correct_answer'],'answer_id' => 0];
                foreach($question['option'] as $opt => $option)
                {
                    $_tmp_gh_question[$ind]['option'][$opt] = ['answer' => $question['option'][$opt],'answer_id' => $opt+1];
                    
                }
              
            }

         
            update_post_meta($post_id, 'gohar_e_hikmat_questions',  $_tmp_gh_question  );
            update_post_meta($post_id, 'gh_release',  $_POST['gh_release']  );
            update_post_meta($post_id, 'gh_answer_display',  $_POST['gh_answer_display']  );
            
            if( ! empty( $_FILES ) && isset( $_FILES['gh_pdf'] ) ) {
                // Upload the goal image to the uploads directory, resize the image, then upload the resized version
                
				$goal_image_file = wp_upload_bits( $_FILES['gh_pdf']['name'], null, @file_get_contents( $_FILES['gh_pdf']['tmp_name'] ) );

				if( false == $goal_image_file['error'] ) {
				
					// Since we've already added the key for this, we'll just update it with the file.
					update_post_meta( $post_id, 'gohar_e_hikmat_pdf', $goal_image_file['url'] );
		
				} // end if/else
			} // end if/else

            if( ! empty( $_FILES ) && isset( $_FILES['gh_pdf_roman'] ) ) {
                // Upload the goal image to the uploads directory, resize the image, then upload the resized version
                
                $goal_image_file = wp_upload_bits( $_FILES['gh_pdf_roman']['name'], null, @file_get_contents( $_FILES['gh_pdf_roman']['tmp_name'] ) );

                if( false == $goal_image_file['error'] ) {
                
                    // Since we've already added the key for this, we'll just update it with the file.
                    update_post_meta( $post_id, 'gohar_e_hikmat_pdf_roman', $goal_image_file['url'] );
        
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

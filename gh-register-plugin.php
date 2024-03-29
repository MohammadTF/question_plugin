<?php

/**

 * @package Gohar_e_Hikmat_Register

 * @version 1.7.2

 */

/*

Plugin Name: Gohar e Hikmat Register

Plugin URI: http://wordpress.org/plugins/hello-dolly/

Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.

Author: Matt Mullenweg

Version: 1.7.2

Author URI: http://ma.tt/

*/



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}



require('init_post_type.php');

require('log.php');



class Gohar_e_Hikmat_Register {



    use log;

    

    const login_form_shortcode        = 'gohar_e_hikmat_login';

    const register_form_shortcode     = 'gohar_e_hikmat_register';

    const member_page_shortcode       = 'gohar_e_hikmat_member';

    const lost_password               = 'gohar_e_hikmat_lost_password';

    const reset_password              = 'gohar_e_hikmat_reset_form';

    const single_page                 = 'gohar_e_hikmat_single_page';
    
    const answer_page                 = 'gohar_e_hikmat_answer_page';



    /**

     * Initializes the plugin.

     *

     * To keep the initialization fast, only add filter and action

     * hooks in the constructor.

     */

    public function __construct() {

        // wp_enqueue_script( 'tt-mobile-menu', 'https://code.jquery.com/jquery-3.4.1.min.js', array('jquery'), '1.0', true );

        add_shortcode( Gohar_e_Hikmat_Register::login_form_shortcode, array( $this, 'render_login_form' ) );

        add_shortcode( Gohar_e_Hikmat_Register::register_form_shortcode, array( $this, 'render_register_form' ) );

        add_shortcode( Gohar_e_Hikmat_Register::lost_password, array( $this, 'render_password_lost_form' ) );

        add_shortcode( Gohar_e_Hikmat_Register::reset_password, array( $this, 'render_password_reset_form' ) );

        add_shortcode( Gohar_e_Hikmat_Register::member_page_shortcode, array( $this, 'render_member_page' ) );

        add_shortcode( Gohar_e_Hikmat_Register::single_page, array( $this, 'render_single_page' ) );

        add_shortcode( Gohar_e_Hikmat_Register::answer_page, array( $this, 'render_answer_page' ) );







        add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );

        add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );

        add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );

        add_action( 'login_form_register', array( $this, 'do_register_user' ) );

        add_action( 'wp_print_footer_scripts', array( $this, 'add_captcha_js_to_footer' ) );

        add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );

        add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );

        add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );

        add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );

        add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );

        add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

        // add_filter( 'wp_new_user_notification_email' , array($this,'edit_user_notification_email'), 10, 3 );







        

        add_filter( 'admin_init' , array( $this, 'register_settings_fields' ) );

        add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );

        add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );

        add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );

       

        // add_filter('manage_users_columns', array( $this,'pippin_add_user_id_column'));

        // add_action('manage_users_custom_column',  array( $this,'pippin_show_user_id_column_content'), 10, 3);
       
        add_action( 'wp_enqueue_scripts', array($this,'my_enqueue') );
        add_action("wp_ajax_save_answer", array($this,"save_answer"));
        
        add_action( 'show_user_profile', array($this,'extra_user_profile_fields' ));
        add_action( 'edit_user_profile', array($this,'extra_user_profile_fields' ));
        // add_action( 'personal_options_update', array($this,'save_extra_user_profile_fields') );
        // add_action( 'edit_user_profile_update', array($this,'save_extra_user_profile_fields') );
    }

    function save_extra_user_profile_fields( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) ) { 
            return false; 
        }
        // update_user_meta( $user_id, 'address', $_POST['address'] );
        // update_user_meta( $user_id, 'postalcode', $_POST['postalcode'] );
    }
    function extra_user_profile_fields( $user ) { ?>
        <h3><?php _e("Extra profile information", "blank"); ?></h3>
    
        <table class="form-table">
        <tr>
            <th><label for="telephone"><?php _e("Telephone"); ?></label></th>
            <td>
                <?php echo esc_attr( get_the_author_meta( 'telephone', $user->ID ) ); ?><br />
                <!-- <span class="description"><?php _e("Please enter your address."); ?></span> -->
            </td>
        </tr>
       
        <tr>
        <th><label for="date_of_birth"><?php _e("Date of birth"); ?></label></th>
            <td>
                <?php echo esc_attr( get_the_author_meta( 'date_of_birth', $user->ID ) ); ?><br />
                <!-- <span class="description"><?php _e("Please enter your postal code."); ?></span> -->
            </td>
        </tr>
        </table>
    <?php }
    function my_enqueue() {

        wp_localize_script( 'my_voter_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        

        wp_enqueue_script( 'my_voter_script' );

    }

    function save_answer(){
        if ( !wp_verify_nonce( $_REQUEST['nonce'], "save_answer")) {
            exit("No naughty business please");
         } 
         
         $question_data = get_post_meta($_POST['post_id'],'gohar_e_hikmat_questions',true);

         $submitted = $_POST['option'];
         $question = $_POST['question'];
         
         $submitted = [$question => $_POST['option']];

         $given_answers = [];

         $score = 0;


         foreach($submitted as $ques => $ans)

         {

            

                 $_TMP = $ans;

               

                 if(isset($question_data[$ques])  && !empty($question_data[$ques]) && isset($question_data[$ques]['title'])  )

                 {
                     
                 
                     $given_answers[$ques]   = [

                         'question'  => $question_data[$ques]['title'],

                         'given_answer'    => $_TMP,

                         'correct_answer'    => $question_data[$ques]['correct_answer']

                     ];

                 }

            

         }

         $user_id = get_current_user_id();



        //  print_r($submitted);
        //  print_r($given_answers);
         
         $saved_ans = get_user_meta($user_id,'_save_answers_'.$_POST['post_id'],true);
         $saved_ans = (json_decode($saved_ans,true));
         $tmp = [];
         if(!empty($saved_ans))
         {
            // $given_answers[key($saved_ans)] = $saved_ans;
            //  array_push($given_answers, $saved_ans);

         }
         $saved_ans[key($given_answers)] = $given_answers[key($given_answers)];
        //  print_r($saved_ans);
          update_user_meta($user_id,'_save_answers_'.$_POST['post_id'],json_encode($saved_ans));
        //   update_user_meta($user_id,'_save_answers_'.$_POST['post_id'],'');
    

          echo 'answer saved';


         die;
    }



    function edit_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {



        $message = sprintf(__( "Welcome to %s! Here's how to log in:" ), $blogname ) . "\r\n";

        $message .= wp_login_url() . "\r\n";

        $message .= sprintf(__( 'Username: %s' ), $user->user_login ) . "\r\n";

        $message .= sprintf(__( 'Password: %s' ), $user->user_pass ) . "\r\n\r\n";

        $message .= sprintf(__( 'If you have any problems, please contact me at %s.'), get_option( 'admin_email' ) ) . "\r\n";

        // $message .= __('Adios!');

        var_dump($wp_new_user_notification_email);

        die;

        $wp_new_user_notification_email['message'] = $message;

    

        return $wp_new_user_notification_email;

    

    }



    function pippin_add_user_id_column($columns) {

        $columns['user_score'] = 'Score';

        return $columns;

    }

        

    function pippin_show_user_id_column_content($value, $column_name, $user_id) {

        $user = get_userdata( $user_id );

        if ( 'user_score' == $column_name )

            return $user_id;

        return $value;

    }

    public function render_answer_page()

    {
        
        if(
            isset($_GET['user_id']) && isset($_GET['topic_id'])
            && !empty($_GET['user_id']) && !empty($_GET['topic_id'])
            && is_numeric($_GET['user_id']) && is_numeric($_GET['topic_id'])
         )
         {
            $user_id        = $_GET['user_id'];
            $post_id        = $_GET['topic_id'];
            $prev_answers   = get_user_meta($user_id,'_review_answers_'.$post_id,true);
            $questions      = get_post_meta($post_id,'gohar_e_hikmat_questions',true);
            $prev_answers   = json_decode($prev_answers,true);
            // echo '<pre>';print_r($prev_answers);die;
            
            if(empty($prev_answers))
            {
                return 'No answer is submitted';
            }
             ob_start();
             ?>
             <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($questions as $id => $ans)
                    {?>
                        <tr>
                            <td><?php echo $ans['title']; ?></td>
                            <?php foreach($ans['option'] as $options)
                            {
                                if($options['answer_id'] == $prev_answers[$id]['given_answer'])
                                {

                                    ?>
                                        <td>
                                            <?php echo $options['answer']; ?>
                                            <small>(<?php echo $ans['correct_answer']['answer_id'] == $prev_answers[$id]['given_answer']?'Correct Answer':'Wrong Answer'; ?>)</small>
                                        </td>
                                    <?php 
                                }
                            }
                            ?>
                        </tr>
                    <?php 
                    }?>
                </tbody>
             </table>
             <?php
     
             $html = ob_get_contents();
     
             ob_end_clean();
     
         
     
             return $html;
         }

    }


    public function render_single_page()

    {
        // delete_user_meta(10,'_review_answers_14');
        if(isset($_POST['submit']) && isset($_POST['option']) && !empty($_POST['option']))

        {

            $questions = get_post_meta($_POST['post_id'],'gohar_e_hikmat_questions',true);

            $submitted = $_POST['option'];

            // var_dump($submitted);die;
            $given_answers = [];

            $score = 0;

            foreach($submitted as $ques => $ans)

            {

                foreach($questions as  $question_id_ =>$question_data)

                {

                    $_TMP = $ans;

                    
                    
                    // $_TMP = array_values($_TMP);

                    // $_TMP = isset($_TMP[0])?$_TMP[0]:'';

                    if($ques === $question_id_  )

                    {

                        $given_answers[$question_id_]   = [

                            'question'  => $question_data['title'],

                            'given_answer'    => $_TMP,

                            'correct_answer'    => $question_data['correct_answer']

                        ];



                        // $given_answers[$question_data['title']] =  $_TMP;
                        if($_TMP == $question_data['correct_answer']['answer_id'])

                        {



                            ++$score;

                           

                        }else{

                            --$score;

                        }

                    }

                }

            }

            


            var_dump(is_user_logged_in());
            if(is_user_logged_in()){

                
                $user_id = get_current_user_id();

                var_dump(['user_id'=> $user_id,'post_id'=>$_POST['post_id']]);



                update_user_meta($user_id,'_given_answers',$_POST['post_id']);

                // update_user_meta($user_id,'_question_'.$_POST['post_id'],json_encode($correct_answers));

                update_user_meta($user_id,'_review_answers_'.$_POST['post_id'],json_encode($given_answers));

                update_user_meta($user_id,'_score_'.$_POST['post_id'],$score);
                

                $log  = "Answer Submit: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.

                "Topic ID: ".$_POST['post_id'].PHP_EOL.

                "Result: ".json_encode($given_answers).PHP_EOL.

                "Score: ".$score.PHP_EOL.

                "-------------------------".PHP_EOL;

                

                log::info($log);





            }

                       

        }

        if(isset($_GET['question_id']) && '' != ($_GET['question_id']) && is_user_logged_in())

        {

            

            $prev_answers = [];
            $save_answer = [];

            $submitted_answers = [];

            $score = 0;
            if(is_user_logged_in()){
                
                $user_id        = get_current_user_id();

                
                $score          = get_user_meta($user_id,'_score_'.$_GET['question_id'],true);
                
                $prev_answers   = get_user_meta($user_id,'_review_answers_'.$_GET['question_id'],true);
                $prev_answers = json_decode($prev_answers,true);

              
                $save_answer   = get_user_meta($user_id,'_save_answers_'.$_GET['question_id'],true);
                $save_answer = json_decode($save_answer,true);
                
               
                log::info($prev_answers);

            }
            if(!empty($prev_answers)){

                echo '<p>You have successfully submitted gohar e hikmat form, please wait for the result date</p>';

                

            }

            else{



            

                $question_id = trim($_GET['question_id']);

                $link = get_post_meta($question_id, 'gohar_e_hikmat_pdf',true);
                $link_roman = get_post_meta($question_id, 'gohar_e_hikmat_pdf_roman',true);
               
                $args = [

                    "posts__in" => [$question_id],

                    "post_type" => "gohar_e_hikmat",

                    'meta_query'  => array(

                        array(          

                            'key'      => 'gh_release',

                            'compare'  => '<',

                            'value'    => date('Y-m-d H:i:s') ,

                            'type'     => 'DATETIME'

                            )

                    ),

                ];

                $query = new WP_Query($args);

               

            

                ob_start();

        

                if($query->have_posts())

                {
                    $nonce = wp_create_nonce("save_answer");
                    $ajax_url = admin_url( 'admin-ajax.php' );

                    ?>
                        <script>
                                  (function($) {

                                    // Use $() inside of this function

                                  $().ready(function(){
                                      $('.radio-save').on('change',function(e){
                                        var nonce = $('#nonce').val();
                                        var post_id = $(this).data('post_id');
                                        var option = $(this).val();
                                        var question = $(this).data('question');
                                        $.ajax({
                                                type : "post",
                                                dataType : "json",
                                                url : "<?php echo $ajax_url;?>",
                                                data : {action: "save_answer",
                                                    nonce: nonce,
                                                     post_id: post_id,
                                                     option:option,
                                                     question:question
                                                    },
                                                success: function(response) {
                                                   console.log(response);
                                                }
                                            })   
                                      });
                                  });
                                

                                })(jQuery);

                                function alertbox(){
                                    var response = confirm('Press ok, if you have marked all answers, otherwise press cancel');
                                    if($response){
                                      	return true;
                                      } else {
                                          return false;
                                      }
                                }
                                  </script>
                    <form action="" method="post" onsubmit="return confirm('Press ok, if you have marked all answers, otherwise press cancel');">
                                <input type="hidden" name="nonce" id="nonce" value="<?php echo $nonce;?>">
                    <?php if($link): ?>

                    <a class="fusion-button button-flat fusion-button-default-shape fusion-button-default-size button-default button-1 fusion-button-default-span fusion-button-default-type" target="_blank" href="<?php echo $link;?>"><span class="fusion-button-text">Open Book</span></a>

                    <?php endif; ?>
                    <?php if($link_roman): ?>

                    <a class="fusion-button button-flat fusion-button-default-shape fusion-button-default-size button-default button-1 fusion-button-default-span fusion-button-default-type" target="_blank" href="<?php echo $link_roman;?>"><span class="fusion-button-text">Open Book(Roman)</span></a>

                    <?php endif; ?>

                    <?php

                    while($query->have_posts()){

                        $query->the_post();

                        $id = get_the_ID();
                        if($id != $question_id) continue;

                        $display_answer = get_post_meta($id, 'gh_answer_display',true);

                        $today = date('Y-m-d');

                        // var_dump([$id, $display_answer < $today,$display_answer , $today]);

                        $questions = get_post_meta($id,'gohar_e_hikmat_questions',true);

                        $pdf       = get_post_meta($id,'gohar_e_hikmat_pdf');

                    

                        //echo 'Score: '.$score;

                        // var_dump($prev_answers);

                        if(false)

                        {

                            // foreach($prev_answers as $q => $ans){

                            //     echo '<p>'.$q.' - '.$ans['given_answer']. ' - Correct answer -' .$ans['correct_answer'].'</p>';

                            // }



                

                        

                        }else{

                            ?>

                              <p><?php //the_content(); ?></p>
                              

                            <?php
                          
                            foreach($questions as $index => $question){

                                // $_TMP = [];

                                // $_TMP = array_merge($question["option"],[$question["correct_answer"]]);
                                
                                // shuffle($_TMP);
                                
                                $_TMP = $question["option"];
                        

                                ?>

                                <h3><?php echo $question["title"];?></h3>

                              

                                <input type="hidden" name="post_id" value="<?php echo $id;?>">

                                <input type="hidden" name="question_id" value="<?php echo $index;?>">

                                <ul style="list-style: none;">

                                <?php foreach($_TMP as $i => $opt)

                                {

                                    ?>

                                    <li><input 

                                        type="radio"

                                        name="option[<?php echo $index;?>]"

                                        data-question="<?php echo $index?>"

                                        value="<?php echo $opt['answer_id'];?>"

                                        data-post_id="<?php echo $id; ?>"

                                        class="radio-save"

                                        <?php
                                        if($save_answer[$index]['given_answer']=='')
                                        {

                                        }else{

                                            echo ($save_answer[$index]['given_answer']==$opt['answer_id'])?'checked':''; 
                                        }
                                         ?>

                                        >

                                    <?php echo $opt['answer'];?></li>

                                    <?php

        

                                }

                                ?></ul>

                                <?php

        

                                

        

                            }

                        }

                        

                    }

                    wp_reset_postdata();

                    ?>

                    <input type="submit" class="fusion-button button-flat fusion-button-default-shape fusion-button-default-size button-default button-1 fusion-button-default-span fusion-button-default-type" value="Submit" name="submit">

                    </form>

                    <?php

                }

            }

            // return $_GET['question_id'];

        }else{

            

           echo '<script> window.location.href = "'.site_url().'/member-register"; </script>';

           die;

        }

        $html = ob_get_contents();

        ob_end_clean();

    

        return $html;

    }

     

    public function render_member_page()

    {

        if(! is_user_logged_in())

        {

            echo '<script> window.location.href = "'.site_url().'/member-register"; </script>';

           die;

        }

        

        $usr = wp_get_current_user();

       

        ob_start();

        if(in_array('administrator',$usr->roles))

        {

            $users = (get_users(["role__not_in"=> "administrator"]));

            $args = array(  

                        'post_type' => 'gohar_e_hikmat',

                        'posts_per_page' => 1,

                        'order' => 'ASC',

                        'meta_query' => array(

                            'relation' => 'AND',

                            array(

                                'key' => 'gh_release',

                                'value' => date('Y-m-d'),

                                'compare' => '>=',

                            ).

                            array(

                                'key' => 'gh_answer_display',

                                'value' => date('Y-m-d'),

                                'compare' => '<=',

                            )

                     )

                    );



                    $topic_id = 0;

                    $topic_title = '';



                    $query = new WP_Query($args);

                    if($query->have_posts()){

                        while($query->have_posts())

                        {

                            $query->the_post();

                            $topic_id = get_the_ID();

                            $topic_title = get_the_title();

                            

                        }

                    }



                    // var_dump($users);

                    ?>

                    <table cellpadding="5" cellspacing="5" border="1" width="80%">

                        <thead>

                            <tr>

                                <th>Email</th>
                                
                                <th>Phone</th>
                                <th>Date of birth</th>

                                <th>Name</th>

                                <th>Topic</th>

                                <th>Score</th>

                                <th>Answers</th>

                            </tr>

                        </thead>

                    <?php

                    foreach($users as $user){

                        $score = 0;

                        // $score = get_user_meta($user->data->ID, '_score_'.$topic_id,true);
                        $submitted = get_user_meta($user->data->ID, '_review_answers_'.$topic_id,true);
                        $submitted = json_decode($submitted,true);

                        $questions = get_post_meta($topic_id,'gohar_e_hikmat_questions',true);
                        $score = 0;

                       
                       
                        if(null != $submitted)
                        {
                            foreach($submitted as $ques => $ans)
                            {

                                foreach($questions as  $question_id_ =>$question_data)
                                {
                                  

                                    if($ques === $question_id_  )
                                    {
                                        // $given_answers[$question_data['title']] =  $_TMP;
                                        if($ans['given_answer'] == $question_data['correct_answer']['answer_id'])

                                        {
                                            ++$score;
                                        }else{
                                            --$score;
                                        }
                                    }

                                }

                            }

                        }
                        
                        




                        ?>

                            <tr>

                                <td><?php echo $user->data->user_email; ?></td>
                                
                                <td> <?php echo get_user_meta($user->data->ID,'telephone',true); ?></td>
                                <td> <?php echo get_user_meta($user->data->ID,'date_of_birth',true); ?></td>

                                <td> <?php echo $user->data->display_name; ?></td>

                                <td> <?php echo $topic_title; ?></td>

                                <td> <?php echo $score; ?></td>
                                
                                <td> <a href="<?php echo site_url().'/answer-page?user_id='.$user->data->ID.'&topic_id='.$topic_id; ?>">View</a></td>

                            </tr>

                        <?php

                    }

                    ?>

                    </table>

                    <?php











        }else{



            

            $args = [

                "post_type" =>"gohar_e_hikmat",

                'meta_query'  => array(
                    'relation' => 'AND',

                    array(          

                        'key'      => 'gh_release',

                        'compare'  => '<',

                        'value'    => date('Y-m-d H:i:s') ,

                        'type'     => 'DATETIME'

                    ),
                    array(          

                        'key'      => 'gh_end',

                        'compare'  => '>',

                        'value'    => date('Y-m-d H:i:s') ,

                        'type'     => 'DATETIME'

                        )


                ),

            ];

            

            $query = new WP_Query($args);

            if($query->have_posts()){

                while($query->have_posts())

                {

                    $query->the_post();

                    ?>

                    <a href="<?php echo home_url( '/single-page/' ).'?question_id='.get_the_ID();?>"><?php echo get_the_title(); ?></a>

                    <?php

                }

                wp_reset_postdata();

            } else {
                echo "No Gohar e Hikmat Edition is available";
            }

        }

        $html = ob_get_contents();

        ob_end_clean();

    

        return $html;

    }

    /**

    * Resets the user's password if the password reset form was submitted.

    */

   public function do_password_reset() {

       if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

           $rp_key = $_REQUEST['rp_key'];

           $rp_login = $_REQUEST['rp_login'];

    

           $user = check_password_reset_key( $rp_key, $rp_login );

    

           if ( ! $user || is_wp_error( $user ) ) {

               if ( $user && $user->get_error_code() === 'expired_key' ) {

                   wp_redirect( home_url( 'member-login?login=expiredkey' ) );

               } else {

                   wp_redirect( home_url( 'member-login?login=invalidkey' ) );

               }

               exit;

           }

    

           if ( isset( $_POST['pass1'] ) ) {

               if ( $_POST['pass1'] != $_POST['pass2'] ) {

                   // Passwords don't match

                   $redirect_url = home_url( 'member-password-reset' );

    

                   $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );

                   $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );

                   $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

    

                   wp_redirect( $redirect_url );

                   exit;

               }

    

               if ( empty( $_POST['pass1'] ) ) {

                   // Password is empty

                   $redirect_url = home_url( 'member-password-reset' );

    

                   $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );

                   $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );

                   $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

    

                   wp_redirect( $redirect_url );

                   exit;

               }

    

               // Parameter checks OK, reset password

               reset_password( $user, $_POST['pass1'] );

               wp_redirect( home_url( 'member-login?password=changed' ) );

           } else {

               echo "Invalid request.";

           }

    

           exit;

       }

   }

    /**

     * Redirects to the custom password reset page, or the login page

     * if there are errors.

     */

    public function redirect_to_custom_password_reset() {

        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {

            // Verify key / login combo

            $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );

            if ( ! $user || is_wp_error( $user ) ) {

                if ( $user && $user->get_error_code() === 'expired_key' ) {

                    wp_redirect( home_url( 'member-login?login=expiredkey' ) );

                } else {

                    wp_redirect( home_url( 'member-login?login=invalidkey' ) );

                }

                exit;

            }

    

            $redirect_url = home_url( 'member-password-reset' );

            $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );

            $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

    

            wp_redirect( $redirect_url );

            exit;

        }

    }

    /**

     * Returns the message body for the password reset mail.

     * Called through the retrieve_password_message filter.

     *

     * @param string  $message    Default mail message.

     * @param string  $key        The activation key.

     * @param string  $user_login The username for the user.

     * @param WP_User $user_data  WP_User object.

     *

     * @return string   The mail message to send.

     */

    public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {

        // Create new message

        $msg  = __( 'Hello!', 'personalize-login' ) . "\r\n\r\n";

        $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'personalize-login' ), $user_login ) . "\r\n\r\n";

        $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'personalize-login' ) . "\r\n\r\n";

        $msg .= __( 'To reset your password, visit the following address:', 'personalize-login' ) . "\r\n\r\n";

        $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";

        $msg .= __( 'Thanks!', 'personalize-login' ) . "\r\n";

    

        return $msg;

    }

    /**

     * Initiates password reset.

     */

    public function do_password_lost() {

        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

            $errors = retrieve_password();

            if ( is_wp_error( $errors ) ) {

                // Errors found

                $redirect_url = home_url( 'member-password-lost' );

                $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );

            } else {

                // Email sent

                $redirect_url = home_url( 'member-login' );

                $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );

            }

    

            wp_redirect( $redirect_url );

            exit;

        }

    }

    /**

     * A shortcode for rendering the form used to initiate the password reset.

     *

     * @param  array   $attributes  Shortcode attributes.

     * @param  string  $content     The text content for shortcode. Not used.

     *

     * @return string  The shortcode output

     */

    public function render_password_lost_form( $attributes, $content = null ) {

        // Parse shortcode attributes

        $default_attributes = array( 'show_title' => false );

        $attributes = shortcode_atts( $default_attributes, $attributes );

    

        if ( is_user_logged_in() ) {

            return __( 'You are already signed in.', 'personalize-login' );

        } else {



            // Retrieve possible errors from request parameters

            $attributes['errors'] = array();

            if ( isset( $_REQUEST['errors'] ) ) {

                $error_codes = explode( ',', $_REQUEST['errors'] );

            

                foreach ( $error_codes as $error_code ) {

                    $attributes['errors'] []= $this->get_error_message( $error_code );

                }

            }











            return $this->get_template_html( 'password_lost_form', $attributes );

        }

    }

    /**

     * Redirects the user to the custom "Forgot your password?" page instead of

     * wp-login.php?action=lostpassword.

     */

    public function redirect_to_custom_lostpassword() {

        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {

            if ( is_user_logged_in() ) {

                $this->redirect_logged_in_user();

                exit;

            }

    

            wp_redirect( home_url( 'member-password-lost' ) );

            exit;

        }

    }

    /**

     * Checks that the reCAPTCHA parameter sent with the registration

     * request is valid.

     *

     * @return bool True if the CAPTCHA is OK, otherwise false.

     */

    private function verify_recaptcha() {

        // This field is set by the recaptcha widget if check is successful

        if ( isset ( $_POST['g-recaptcha-response'] ) ) {

            $captcha_response = $_POST['g-recaptcha-response'];

        } else {

            return false;

        }

    

        // Verify the captcha response from Google

        $response = wp_remote_post(

            'https://www.google.com/recaptcha/api/siteverify',

            array(

                'body' => array(

                    'secret' => get_option( 'personalize-login-recaptcha-secret-key' ),

                    'response' => $captcha_response

                )

            )

        );

    

        $success = false;

        if ( $response && is_array( $response ) ) {

            $decoded_response = json_decode( $response['body'] );

            $success = $decoded_response->success;

        }

    

        return $success;

    }

    /**

     * An action function used to include the reCAPTCHA JavaScript file

     * at the end of the page.

     */

    public function add_captcha_js_to_footer() {

        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";

    }

    /**

     * * Registers the settings fields needed by the plugin.

    */

    public function register_settings_fields() {

        // Create settings fields for the two keys used by reCAPTCHA

        register_setting( 'general', 'personalize-login-recaptcha-site-key' );

        register_setting( 'general', 'personalize-login-recaptcha-secret-key' );

        

        add_settings_field(

            'personalize-login-recaptcha-site-key',

            '<label for="personalize-login-recaptcha-site-key">' . __( 'reCAPTCHA site key' , 'personalize-login' ) . '</label>',

            array( $this, 'render_recaptcha_site_key_field' ),

            'general'

        );

        

        add_settings_field(

            'personalize-login-recaptcha-secret-key',

            '<label for="personalize-login-recaptcha-secret-key">' . __( 'reCAPTCHA secret key' , 'personalize-login' ) . '</label>',

            array( $this, 'render_recaptcha_secret_key_field' ),

            'general'

        );

    }

        

    public function render_recaptcha_site_key_field() {

        $value = get_option( 'personalize-login-recaptcha-site-key', '' );

        echo '<input type="text" id="personalize-login-recaptcha-site-key" name="personalize-login-recaptcha-site-key" value="' . esc_attr( $value ) . '" />';

    }

        

    public function render_recaptcha_secret_key_field() {

        $value = get_option( 'personalize-login-recaptcha-secret-key', '' );

        echo '<input type="text" id="personalize-login-recaptcha-secret-key" name="personalize-login-recaptcha-secret-key" value="' . esc_attr( $value ) . '" />';

    }



    /**

     * Handles the registration of a new user.

     *

     * Used through the action hook "login_form_register" activated on wp-login.php

     * when accessed through the registration action.

     */

    public function do_register_user() {

        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

            $redirect_url = home_url( 'member-register' );

    

            if ( ! get_option( 'users_can_register' ) ) {

                // Registration closed, display error

                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );

            } elseif ( ! $this->verify_recaptcha() && false) { // TODO add recaptcha details from google recaptcha

                // Recaptcha check failed, display error

                $redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );

            } else {

                $email = $_POST['email'];

                $first_name = sanitize_text_field( $_POST['first_name'] );

                $last_name = sanitize_text_field( $_POST['last_name'] );

                $country_id = sanitize_text_field( $_POST['country_id'] );

                $login_name = sanitize_text_field( $_POST['login_name'] );

                $date_of_birth = sanitize_text_field( $_POST['date_of_birth'] );

                $nic_passport = sanitize_text_field( $_POST['nic_passport'] );

                $street_address = sanitize_text_field( $_POST['street_address'] );

                $telephone = sanitize_text_field( $_POST['telephone'] );

             

                $result = $this->register_user( $email, $first_name, $last_name,$country_id,

                $login_name,

                 $date_of_birth,

                $nic_passport,

                 $street_address,

                                  $telephone );

             

                if ( is_wp_error( $result ) ) {

                    // Parse errors into a string and append as parameter to redirect

                    $errors = join( ',', $result->get_error_codes() );

                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );

                } else {

                    // Success, redirect to login page.

                    $redirect_url = home_url( 'member-login' );

                    $redirect_url = add_query_arg( 'registered', $email, $redirect_url );

                }

            }

    

            wp_redirect( $redirect_url );

            exit;

        }

    }

    /**

     * Validates and then completes the new user signup process if all went well.

     *

     * @param string $email         The new user's email address

     * @param string $first_name    The new user's first name

     * @param string $last_name     The new user's last name

     *

     * @return int|WP_Error         The id of the user that was created, or error if failed.

     */

    private function register_user( 

        $email, 

        $first_name, 

        $last_name,

        $country_id,

        $login_name, $date_of_birth,

        $nic_passport,

        $street_address,

        $telephone

    ) {

        $errors = new WP_Error();

    

        // Email address is used as both username and email. It is also the only

        // parameter we need to validate

        if ( ! is_email( $email ) ) {

            $errors->add( 'email', $this->get_error_message( 'email' ) );

            return $errors;

        }

    

        if ( username_exists( $email ) || email_exists( $email ) ) {

            $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );

            return $errors;

        }

         if ( username_exists( $login_name ) ) {

            $errors->add( 'username_exists', $this->get_error_message( 'username_exists') );

            return $errors;

        }

    

        // Generate the password so that the subscriber will have to check email...

        $password = wp_generate_password( 12, false );

        $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.

        "Username: ".$email.PHP_EOL.

        "Password: ".$password.PHP_EOL.

        "-------------------------".PHP_EOL;

        log::info($log);

    

        $user_data = array(

            'user_login'    => $login_name,

            'user_nicename'    => $login_name,

            'user_email'    => $email,

            'user_pass'     => $password,

            'first_name'    => $first_name,

            'last_name'     => $last_name,

            'nickname'      => $login_name,

        );

    

        $user_id = wp_insert_user( $user_data );

        

        wp_new_user_notification( $user_id, $password );



        add_user_meta($user_id,'date_of_birth',$date_of_birth);

        add_user_meta($user_id,'nic_passport',$nic_passport);

        add_user_meta($user_id,'street_address',$street_address);

        add_user_meta($user_id,'telephone',$telephone);

        add_user_meta($user_id,'country_id',$country_id);



        return $user_id;

    }



    /**

     * Redirects the user to the custom registration page instead

     * of wp-login.php?action=register.

     */

    public function redirect_to_custom_register() {

        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {

            if ( is_user_logged_in() ) {

                $this->redirect_logged_in_user();

            } else {

                wp_redirect( home_url( 'member-register' ) );

            }

            exit;

        }

    }



    /**

     * A shortcode for rendering the new user registration form.

     *

     * @param  array   $attributes  Shortcode attributes.

     * @param  string  $content     The text content for shortcode. Not used.

     *

     * @return string  The shortcode output

     */

    public function render_register_form( $attributes, $content = null ) {

        

        if(empty($attributes))

        {

            $attributes = [];            

        }

        // Check if the user just registered

        $attributes['registered'] = isset( $_REQUEST['registered'] );

        // Retrieve recaptcha key

    

        // Parse shortcode attributes

        $default_attributes = array( 'show_title' => false );

        

        $attributes = shortcode_atts( $default_attributes, $attributes );

        $attributes['recaptcha_site_key'] = get_option( 'personalize-login-recaptcha-site-key', null );

        if ( is_user_logged_in() ) {

            return __( 'You are already signed in.', 'personalize-login' );

        } elseif ( ! get_option( 'users_can_register' ) ) {

            return __( 'Registering new users is currently not allowed.', 'personalize-login' );

        } else {

            // Retrieve possible errors from request parameters

            $attributes['errors'] = array();

            if ( isset( $_REQUEST['register-errors'] ) ) {

                $error_codes = explode( ',', $_REQUEST['register-errors'] );

            

                foreach ( $error_codes as $error_code ) {

                    $attributes['errors'] []= $this->get_error_message( $error_code );

                }

            }

            return $this->get_template_html( 'register_form', $attributes );

        }

    }

    /**

     * A shortcode for rendering the form used to reset a user's password.

     *

     * @param  array   $attributes  Shortcode attributes.

     * @param  string  $content     The text content for shortcode. Not used.

     *

     * @return string  The shortcode output

     */

    public function render_password_reset_form( $attributes, $content = null ) {

        // Parse shortcode attributes

        $default_attributes = array( 'show_title' => false );

        $attributes = shortcode_atts( $default_attributes, $attributes );

    

        if ( is_user_logged_in() ) {

            return __( 'You are already signed in.', 'personalize-login' );

        } else {

            if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {

                $attributes['login'] = $_REQUEST['login'];

                $attributes['key'] = $_REQUEST['key'];

    

                // Error messages

                $errors = array();

                if ( isset( $_REQUEST['error'] ) ) {

                    $error_codes = explode( ',', $_REQUEST['error'] );

    

                    foreach ( $error_codes as $code ) {

                        $errors []= $this->get_error_message( $code );

                    }

                }

                $attributes['errors'] = $errors;

    

                return $this->get_template_html( 'password_reset_form', $attributes );

            } else {

                return __( 'Invalid password reset link.', 'personalize-login' );

            }

        }

    }

    /**

     * Returns the URL to which the user should be redirected after the (successful) login.

     *

     * @param string           $redirect_to           The redirect destination URL.

     * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.

     * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.

     *

     * @return string Redirect URL

     */

    public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

        $redirect_url = home_url();

    

        if ( ! isset( $user->ID ) ) {

            return $redirect_url;

        }

    

        if ( user_can( $user, 'manage_options' ) ) {

            // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.

            if ( $requested_redirect_to == '' ) {

                $redirect_url = admin_url();

            } else {

                $redirect_url = $requested_redirect_to;

            }

        } else {

            // Non-admin users always go to their account page after login

            $redirect_url = home_url( 'member-account' );

        }

    

        return wp_validate_redirect( $redirect_url, home_url() );

    }

    /**

     * Redirect to custom login page after the user has been logged out.

     */

    public function redirect_after_logout() {

        $redirect_url = home_url( 'member-login?logged_out=true' );

        wp_safe_redirect( $redirect_url );

        exit;

    }



    /**

     * Redirect the user after authentication if there were any errors.

     *

     * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.

     * @param string            $username   The user name used to log in.

     * @param string            $password   The password used to log in.

     *

     * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.

     */

    function maybe_redirect_at_authenticate( $user, $username, $password ) {

        // Check if the earlier authenticate filter (most likely, 

        // the default WordPress authentication) functions have found errors

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            if ( is_wp_error( $user ) ) {

                $error_codes = join( ',', $user->get_error_codes() );

    

                $login_url = home_url( 'member-login' );

                $login_url = add_query_arg( 'login', $error_codes, $login_url );

    

                wp_redirect( $login_url );

                exit;

            }

        }

    

        return $user;

    }

    /**

     * Finds and returns a matching error message for the given error code.

     *

     * @param string $error_code    The error code to look up.

     *

     * @return string               An error message.

     */

    private function get_error_message( $error_code ) {

        switch ( $error_code ) {

            case 'empty_username':

                return __( 'You do have an email address, right?', 'personalize-login' );

    

            case 'empty_password':

                return __( 'You need to enter a password to login.', 'personalize-login' );

    

            case 'invalid_username':

                return __(

                    "We don't have any users with that email address. Maybe you used a different one when signing up?",

                    'personalize-login'

                );

    

            case 'incorrect_password':

                $err = __(

                    "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",

                    'personalize-login'

                );

                return sprintf( $err, wp_lostpassword_url() );

            case 'email':

                return __( 'The email address you entered is not valid.', 'personalize-login' );

            

            case 'email_exists':

                return __( 'An account exists with this email address.', 'personalize-login' );

            

            case 'closed':

                return __( 'Registering new users is currently not allowed.', 'personalize-login' );

            case 'captcha':

                return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'personalize-login' );

            case 'empty_username':

                return __( 'You need to enter your email address to continue.', 'personalize-login' );

             

            case 'invalid_email':

            case 'invalidcombo':

                return __( 'There are no users registered with this email address.', 'personalize-login' );

            case 'expiredkey':

            case 'invalidkey':

                return __( 'The password reset link you used is not valid anymore.', 'personalize-login' );

                

            case 'password_reset_mismatch':

                return __( "The two passwords you entered don't match.", 'personalize-login' );

                    

            case 'password_reset_empty':

                return __( "Sorry, we don't accept empty passwords.", 'personalize-login' );

            case 'username_exists':

                return __( "Username already exists.", 'personalize-login' );

                

            default:

                break;

        }

        

        return __( 'An unknown error occurred. Please try again later.', 'personalize-login' );

    }

    /**

     * A shortcode for rendering the login form.

     *

     * @param  array   $attributes  Shortcode attributes.

     * @param  string  $content     The text content for shortcode. Not used.

     *

     * @return string  The shortcode output

     */

    public function render_login_form( $attributes, $content = null ) {

        

        // Parse shortcode attributes

        $default_attributes = array( 'show_title' => false );

        $attributes = shortcode_atts( $default_attributes, $attributes );

        $show_title = $attributes['show_title'];

    

        if ( is_user_logged_in() ) {

            return __( 'You are already signed in.', 'personalize-login' );

        }

        

        // Pass the redirect parameter to the WordPress login functionality: by default,

        // don't specify a redirect, but if a valid redirect URL has been passed as

        // request parameter, use it.

        $attributes['redirect'] = '';

        if ( isset( $_REQUEST['redirect_to'] ) ) {

            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );

        }

        // Error messages

        $errors = array();

        if ( isset( $_REQUEST['login'] ) ) {

            $error_codes = explode( ',', $_REQUEST['login'] );

        

            foreach ( $error_codes as $code ) {

                $errors []= $this->get_error_message( $code );

            }

        }

        $attributes['errors'] = $errors;



        $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;



        // Check if the user just requested a new password 

        $attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';



        // Check if user just updated password

        $attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';



        // Render the login form using an external template

        return $this->get_template_html( 'login_form', $attributes );

    }



    /**

     * Renders the contents of the given template to a string and returns it.

     *

     * @param string $template_name The name of the template to render (without .php)

     * @param array  $attributes    The PHP variables for the template

     *

     * @return string               The contents of the template.

     */

    private function get_template_html( $template_name, $attributes = null ) {

        if ( ! $attributes ) {

            $attributes = array();

        }

    

        ob_start();

    

        do_action( 'personalize_login_before_' . $template_name );

    

        require( 'templates/' . $template_name . '.php');

    

        do_action( 'personalize_login_after_' . $template_name );

    

        $html = ob_get_contents();

        ob_end_clean();

    

        return $html;

    }



    /**

     * Redirect the user to the custom login page instead of wp-login.php.

     */

    function redirect_to_custom_login() {

        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

        

            if ( is_user_logged_in() ) {

                $this->redirect_logged_in_user( $redirect_to );

                exit;

            }

    

            // The rest are redirected to the login page

            $login_url = home_url( 'member-login' );

            if ( ! empty( $redirect_to ) ) {

                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );

            }

    

            wp_redirect( $login_url );

            exit;

        }

    }



    /**

     * Redirects the user to the correct page depending on whether he / she

     * is an admin or not.

     *

     * @param string $redirect_to   An optional redirect_to URL for admin users

     */

    private function redirect_logged_in_user( $redirect_to = null ) {

        $user = wp_get_current_user();

        if ( user_can( $user, 'manage_options' ) ) {

            if ( $redirect_to ) {

                wp_safe_redirect( $redirect_to );

            } else {

                wp_redirect( admin_url() );

            }

        } else {

            wp_redirect( home_url( 'member-account' ) );

        }

    }

    /**

     * Plugin activation hook.

     *

     * Creates all WordPress pages needed by the plugin.

     */

    public static function plugin_activated() {

        // Information needed for creating the plugin's pages

        $page_definitions = array(

            'member-login' => array(

                'title' => __( 'Sign In', 'personalize-login' ),

                'content' => '['.Gohar_e_Hikmat_Register::login_form_shortcode.']'

            ),

            'member-account' => array(

                'title' => __( 'Your Account', 'personalize-login' ),

                'content' => '['.Gohar_e_Hikmat_Register::member_page_shortcode.']'

            ),

            'member-register' => array(

                'title' => __( 'Register', 'personalize-login' ),

                'content' => '['.Gohar_e_Hikmat_Register::register_form_shortcode.']'

            ),

            'member-password-lost' => array(

                'title' => __( 'Forgot Your Password?', 'personalize-login' ),

                'content' => '['.Gohar_e_Hikmat_Register::lost_password.']' //custom-password-lost-form

            ),

            'member-password-reset' => array(

                'title' => __( 'Pick a New Password', 'personalize-login' ),

                'content' => '['.Gohar_e_Hikmat_Register::reset_password.']' //custom-password-reset-form

            ),

            'single-page' => array(

                'title' => __( 'Question / Answers', 'personalize-login' ),

                'content' => '['.Gohar_e_Hikmat_Register::single_page.']' //custom-password-reset-form

            ),
            'answer-page' => array(

                'title' => __( 'Answers', 'personalize-login' ),

                'content' => '['.Gohar_e_Hikmat_Register::answer_page.']' //custom-password-reset-form

            )

        );

    

        foreach ( $page_definitions as $slug => $page ) {

            // Check that the page doesn't exist already

            $query = new WP_Query( 'pagename=' . $slug );

            if ( ! $query->have_posts() ) {

                // Add the page using the data from the array above

                wp_insert_post(

                    array(

                        'post_content'   => $page['content'],

                        'post_name'      => $slug,

                        'post_title'     => $page['title'],

                        'post_status'    => 'publish',

                        'post_type'      => 'page',

                        'ping_status'    => 'closed',

                        'comment_status' => 'closed',

                    )

                );

            }

        }

    }

     

}

 

// Initialize the plugin

Gohar_e_Hikmat_Questions::getInstance();



$gohar_e_hikmat_register = new Gohar_e_Hikmat_Register();

register_activation_hook( __FILE__, array( 'Gohar_e_Hikmat_Register', 'plugin_activated' ) );


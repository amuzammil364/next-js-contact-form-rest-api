<?php

namespace App\Admin;

use WP_REST_Request;

class FormCPTController {

    public function __construct(){

        add_action('init', [$this,'create_form_cpt']);

        add_action('add_meta_boxes', [$this,'add_form_meta_boxes']);

        add_action('save_post', [$this, 'save_form_meta_boxes']);

        add_action('rest_api_init', [$this,'register_contact_form_endpoint']);


    }

    // 1) Register Form Post_type

    public function create_form_cpt() {
        $labels = array(
            'name' => 'Forms',
            'singular_name' => 'Form',
            'menu_name' => 'Forms',
            'name_admin_bar' => 'Form',
        );
    
        $args = array(
            'labels' => $labels,
            'public' => true,
            'supports' => array('title'),
            'show_in_rest' => true,
        );
    
        register_post_type('form', $args);
    }


    // 2) creat custom meta field for email and message and saving them

    public function add_form_meta_boxes() {
        add_meta_box(
            'form_meta_box',
            'Form Details',
            [$this,'render_form_meta_box'],
            'form',
            'normal',
            'high'
        );
    }
    
    public function render_form_meta_box($post) {
        $email = get_post_meta($post->ID, '_form_email', true);
        $message = get_post_meta($post->ID, '_form_message', true);
        ?>
        <p>
            <label for="form_email">Email:</label>
            <br>
            <input type="email" name="form_email" id="form_email" value="<?php echo esc_attr($email); ?>" />
        </p>
        <p>
            
            <label for="form_message">Message:</label>
            <br>
            <textarea name="form_message" rows="4" cols="50" id="form_message"><?php echo esc_textarea($message); ?></textarea>
        </p>
        <?php
    }
    
    function save_form_meta_boxes($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
        if (isset($_POST['form_email'])) {
            update_post_meta($post_id, '_form_email', sanitize_email($_POST['form_email']));
        }
    
        if (isset($_POST['form_message'])) {
            update_post_meta($post_id, '_form_message', sanitize_text_field($_POST['form_message']));
        }
    }

    // 3) creating Rest API endpoint

    public function register_contact_form_endpoint() {
        register_rest_route('muzammil/v1', '/contact-form', array(
            'methods' => 'POST',
            'callback' => [$this,'handle_contact_form_submission'],
            'permission_callback' => '__return_true',
        ));
    }
    
    public function handle_contact_form_submission(\WP_REST_Request $request) {
        
        // return $request;
        
        $name = sanitize_text_field($request->get_param('name'));
        $email = sanitize_email($request->get_param('email'));
        $message = sanitize_textarea_field($request->get_param('message'));
        $token = $request->get_param('token');

        // token authentication for security
        $auth_token = get_option('next_js_rest_api_secret_key');
        if($token != $auth_token){
            return new \WP_Error('unauthenticated', 'Authentication failed', array('status' => 401));
        }

        if (empty($name) || empty($email) || empty($message)) {
            return new \WP_Error('missing_fields', 'Name, email, and message are required.', array('status' => 400));
        }
    
        $existing_form = new \WP_Query(array(
            'post_type' => 'form',
            'meta_query' => array(
                array(
                    'key' => '_form_email',
                    'value' => $email,
                    'compare' => '='
                )
            ),
            'date_query' => array(
                array(
                    'after' => '1 hour ago'
                )
            )
        ));
    
        if ($existing_form->have_posts()) {
            return new \WP_Error('duplicate_submission', 'A form with this email has already been submitted within the past hour.', array('status' => 400));
        }
    
        $new_form_id = wp_insert_post(array(
            'post_title' => $name,
            'post_type' => 'form',
            'post_status' => 'publish'
        ));
    
        if (is_wp_error($new_form_id)) {
            return new \WP_Error('form_submission_failed', 'Failed to create new form.', array('status' => 500));
        }
    
        update_post_meta($new_form_id, '_form_email', $email);
        update_post_meta($new_form_id, '_form_message', $message);
    
        return new \WP_REST_Response('Form submitted successfully.', 200);
    }


}
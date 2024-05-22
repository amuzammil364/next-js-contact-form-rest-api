<?php

namespace App\Admin;


class MenusController {

    private $secret_key;
    public function __construct(){
        add_action('admin_menu',[$this,'addMenu']);
        $this->secret_key = get_option('next_js_rest_api_secret_key');
    }

    public function addMenu(){
        add_menu_page( 
            __( 'Rest API settings' ),
            'Rest API settings',
            'manage_options',
            'next-js-rest-api-settings',
            [$this,'next_js_rest_api_settings_page_content'],
            '',
            6
        ); 
    }

    public function next_js_rest_api_settings_page_content() {
        echo "<h2>Rest API Secret Key</h2>";
        echo "<p>please add the below key to your requst parameter as <strong>token</strong><br> </p>";
        echo "<p><strong>KEY</strong> => ".$this->secret_key."</p>";
        echo "<p><strong>Rest API Endpoint</strong> => ".site_url()."/wp-json/muzammil/v1/contact-form</p>";
        ;

    }




}
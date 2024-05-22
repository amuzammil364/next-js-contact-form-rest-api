<?php

namespace App\Admin;


class FormCPTController {

    public function __construct(){

        // $this->genrate_secret_key();

    }


    public function genrate_secret_key(){
        $key = get_option('next_js_rest_api_secret_key');
        if(empty($key)){
            $key = md5(microtime().rand());
            update_option( 'next_js_rest_api_secret_key', $key );
        }

    }

}
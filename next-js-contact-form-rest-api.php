<?php
/**

 * Plugin Name:       Next js Contact form Rest-API plugin

 * Plugin URI:        https://noderavel.com/

 * Description:       Accepts Next js Contact form Submissions

 * Version:           1.0.0

 * Requires at least: 5.5

 * Requires PHP:      7.2

 * Author:            Muzammil Ahmed

 * Author URI:        https://noderavel.com/

 * License:           GPL v2 or later

 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html

 */



 // Direct access protection

 defined('ABSPATH') or die();


 // composer autoload

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {

	require_once dirname( __FILE__ ) . '/vendor/autoload.php';

}


use App\Admin\SecretKeyController;
use App\Admin\MenusController;
use App\Admin\FormCPTController;


new SecretKeyController;
new MenusController;
new FormCPTController;


<?php
/*
Plugin Name: Rframe
Plugin URI: http://www.ohmybox.info
Description: Add generated HTML code from R and convert it as an iframe
Version: 0.2
Author: Laurence/OhMyBox.info
Author URI: http://www.ohmybox.info
Text domain: rframe
Domain Path: /languages/
License: GPL2
RFrame is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
RFrame is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/

if ( ! defined( 'ABSPATH' ) ) exit; 

function rframe_init() {
 load_plugin_textdomain( 'rframe', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
add_action('init', 'rframe_init');

require_once('Rframe-post.php' );
?>
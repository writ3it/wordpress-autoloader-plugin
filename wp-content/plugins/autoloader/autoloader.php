<?php

/**
 * Plugin Name: Autoloader
 * Plugin URI:  https://github.com/writ3it/wordpress-autoloader-plugin
 * Description: Plugin provides PSR-4 autoloader for another plugins and themes.
 * License:     MIT
 * License URI: https://github.com/writ3it/wordpress-autoloader-plugin/blob/master/README.md
 * Version: 1.0.0
 * Author: writ3it
 * Text Domain: autoloader
 */

register_activation_hook( __FILE__, 'autoloader_activate_plugin');
register_deactivation_hook( __FILE__, 'autoloader_deactivate_plugin' );
add_action( 'upgrader_process_complete', 'autoloader_upgrade_completed', 10, 2 );


$muModuleName = '!!!autoloader.php';

function autoloader_get_plugin_path(){
    global $muModuleName;
    return WPMU_PLUGIN_DIR."/$muModuleName";
}

function autoloader_activate_plugin(){
    global $muModuleName;
    if (!is_dir(WPMU_PLUGIN_DIR)){
        mkdir(WPMU_PLUGIN_DIR,0755);
    }
    copy($muModuleName,autoloader_get_plugin_path());
}

function autoloader_deactivate_plugin() {
    $path = autoloader_get_plugin_path();
    if (file_exists($path)){
        unlink($path);
    }
}


function autoloader_upgrade_completed($upgrader_object, $options){
    $our_plugin = plugin_basename( __FILE__ );
    if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
        foreach( $options['plugins'] as $plugin ) {
            if( $plugin == $our_plugin ) {
                autoloader_deactivate_plugin();
                autoloader_activate_plugin();
                break;
            }
        }
    }
}

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

class AutoloaderInstaller{

    public function autoloader_upgrade_completed($upgrader_object, $options){
        // TODO: not tested
        $our_plugin = plugin_basename( __FILE__ );
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == $our_plugin ) {
                    $this->autoloader_deactivate_plugin();
                    $this->autoloader_activate_plugin();
                    break;
                }
            }
        }
    }

    public function autoloader_deactivate_plugin() {
        $path = $this->autoloader_get_plugin_path();
        if (file_exists($path)){
            unlink($path);
        }
    }

    private function autoloader_get_plugin_path(){
        $muModuleName = $this->autoloader_get_plugin_name();
        return WPMU_PLUGIN_DIR."/$muModuleName";
    }

    private function autoloader_get_plugin_name(){
        return '!!!autoloader.php';
    }

    public function autoloader_activate_plugin(){
        $muModuleName = $this->autoloader_get_plugin_name();
        if (!is_dir(WPMU_PLUGIN_DIR)) {
            mkdir(WPMU_PLUGIN_DIR, 0755);
        }
        $path = $this->autoloader_get_plugin_path();
        copy(__DIR__ . "/mu-plugin/$muModuleName",$path);
    }


}

$___installer = new AutoloaderInstaller();
register_activation_hook( __FILE__, array($___installer,'autoloader_activate_plugin'));
register_deactivation_hook( __FILE__, array($___installer,'autoloader_deactivate_plugin'));
add_action( 'upgrader_process_complete', array($___installer,'autoloader_upgrade_completed'), 10, 2 );
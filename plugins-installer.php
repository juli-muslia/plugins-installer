<?php
/**
 * Plugin Name: Plugins Installer
 * Plugin URI: #
 * Description: This plugin installs necessary plugins from their WordPress directory links.
 * Version: 1.0
 * Author: Julian Muslia
 * Author URI: https://julianmuslia.com/
 */

// Enqueue jQuery for AJAX requests
function plinst_enqueue_scripts() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'plinst_enqueue_scripts' );

// Register plugin activation hook
register_activation_hook( __FILE__, 'plinst_activate_plugin' );

// Plugin activation function
function plinst_activate_plugin() {
    // Run the function to download and extract ZIP files
    plinst_download_and_extract_files();
}

// Download and extract ZIP files
function plinst_download_and_extract_files() {
    // Array of ZIP file links
    $zip_urls = array(
        'https://downloads.wordpress.org/plugin/wps-hide-login.1.9.9.zip',
        'https://downloads.wordpress.org/plugin/updraftplus.1.23.9.zip',
        'https://downloads.wordpress.org/plugin/insert-headers-and-footers.2.1.2.zip',
        'https://downloads.wordpress.org/plugin/wordfence.7.10.3.zip',
        'https://downloads.wordpress.org/plugin/wordpress-seo.21.0.zip',
        'https://downloads.wordpress.org/plugin/limit-login-attempts-reloaded.2.25.23.zip',
        'https://downloads.wordpress.org/plugin/complianz-gdpr.6.5.3.zip',
        'https://downloads.wordpress.org/plugin/really-simple-ssl.7.0.8.zip',
        'https://downloads.wordpress.org/plugin/host-webfonts-local.5.6.7.zip',
    );

    // Loop through the array of ZIP file links
    foreach ( $zip_urls as $zip_url ) {
        // Get the file name from the URL
        $file_name = basename( $zip_url );

        // Destination path to save the ZIP file
        $zip_path = WP_CONTENT_DIR . '/plugins/' . $file_name;

        // Download the ZIP file
        $download = file_put_contents( $zip_path, fopen( $zip_url, 'r' ) );

        // If the ZIP file is downloaded successfully
        if ( $download !== false ) {
            // Create a new zip archive instance
            $zip = new ZipArchive;

            // Open the ZIP file
            if ( $zip->open( $zip_path ) === true ) {
                // Destination path to extract the ZIP file
                $extract_path = WP_CONTENT_DIR . '/plugins/';

                // Extract the ZIP file
                $zip->extractTo( $extract_path );

                // Close the ZIP file
                $zip->close();

                // Delete the ZIP file
                unlink( $zip_path );
            } else {
                // Error message if the ZIP file cannot be opened
                error_log( 'Unable to open the ZIP file: ' . $file_name );
            }
        } else {
            // Error message if the ZIP file cannot be downloaded
            error_log( 'Unable to download the ZIP file: ' . $zip_url );
        }
    }
}
?>
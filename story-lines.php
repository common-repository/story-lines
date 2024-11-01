<?php
/**
 * Plugin Name:       Story Lines
 * Plugin URI:        http://www.jacobmartella.com/wordpress/wordpress-plugins/story-lines
 * Description:       Add a list of story highlights at the top of your posts to let your readers really know what your story is all about.
 * Version:           2.1
 * Author:            Jacob Martella Web Development
 * Author URI:        https://jacobmartella.com
 * Text Domain:       story-lines
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 *
 * @package    Story_Lines
 * @subpackage Story_Lines/includes
 */

namespace Story_Lines;

// If this file is called directly, then about execution.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jm-starter-plugin-activator.php
 *
 * @since 1.0.0
 */
function activate_story_lines() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-story-lines-activator.php';
	Story_Lines_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-starter-plugin-deactivator.php
 *
 * @since 1.0.0
 */
function deactivate_story_lines() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-story-lines-deactivator.php';
	Story_Lines_Deactivator::deactivate();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_story_lines' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate_story_lines' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-story-lines.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_story_lines() {

	$spmm = new Story_Lines();
	$spmm->run();

}

// Call the above function to begin execution of the plugin.
run_story_lines();

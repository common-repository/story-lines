<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://jacobmartella.com
 * @since      1.0.0
 *
 * @package    Story_Lines
 * @subpackage Story_Lines/includes
 */

namespace Story_Lines;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Story_Lines
 * @subpackage Story_Lines/includes
 */
class Story_Lines {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Story_Lines_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Builds the main object for the plugin.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		$this->plugin_slug = 'story-lines';
		$this->version     = '2.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_setup_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_block_hooks();

	}

	/**
	 * Loads all of the files we're depending on to run the plugin.
	 *
	 * @since  1.0.0
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-story-lines-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-story-lines-setup.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-story-lines-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-story-lines-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-story-lines-database-updates.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'blocks/class-story-lines-blocks.php';

		require_once plugin_dir_path( __FILE__ ) . 'class-story-lines-loader.php';
		$this->loader = new Story_Lines_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the JM_Client_Manager_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Story_Lines_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Runs all of the setup functions for the plugin.
	 *
	 * @since 1.0.0
	 */
	private function define_setup_hooks() {
		$plugin_setup = new Story_Lines_Setup( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Runs all of the admin hooks for the plugin.
	 *
	 * @since 1.0.0
	 */
	private function define_admin_hooks() {
		$admin = new Story_Lines_Admin( $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
		$this->loader->add_action( 'contextual_help', $admin, 'contextual_help', 10, 3 );
		$this->loader->add_action( 'admin_init', $admin, 'add_meta_box' );
		$this->loader->add_action( 'save_post', $admin, 'meta_box_save' );
		$this->loader->add_action( 'init', $admin, 'story_lines_buttons' );
	}

	/**
	 * Runs all of the public hooks for the plugin.
	 *
	 * @since 1.0.0
	 */
	private function define_public_hooks() {
		$public = new Story_Lines_Public( $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_styles' );
		$this->loader->add_action( 'init', $public, 'register_shortcode' );
		$this->loader->add_action( 'widgets_init', $public, 'register_widget' );
	}

	/**
	 * Runs all of the admin hooks for the plugin.
	 *
	 * @since 2.1.0
	 */
	private function define_block_hooks() {
		$blocks = new Story_Lines_Blocks( $this->get_version() );
		$this->loader->add_action( 'init', $blocks, 'create_blocks' );
	}

	/**
	 * Runs any updates needed to the database
	 *
	 * @since 1.1.0
	 */
	private function update_database() {
		$database = new Story_Lines_Database_Updates();
		update_option( 'story_lines_version', $this->version );
	}

	/**
	 * Runs the plugin set up.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Gets the current version of the plugin.
	 *
	 * @since  1.0.0
	 * @return string    The version of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Story_Lines_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}

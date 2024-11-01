<?php
/**
 * Holds all of the admin side functions.
 *
 * PHP version 7.3
 *
 * @link       https://jacobmartella.com
 * @since      1.0.0
 *
 * @package    Story_Lines
 * @subpackage Story_Lines/admin
 */

namespace Story_Lines;

/**
 * Runs the admin side.
 *
 * This class defines all code necessary to run on the admin side of the plugin.
 *
 * @since      1.0.0
 * @package    Story_Lines
 * @subpackage Story_Lines/admin
 */
class Story_Lines_Admin {

	/**
	 * Version of the plugin.
	 *
	 * @since 1.0.0
	 * @var string $version Description.
	 */
	private $version;


	/**
	 * Builds the Story_Lines_Admin object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $version Version of the plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
	}

	/**
	 * Enqueues the styles for the admin side of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		global $typenow;
		if ( 'post' === $typenow || 'page' === $typenow ) {
			wp_enqueue_style( 'story-lines-admin', plugin_dir_url( __FILE__ ) . 'css/admin-style.min.css', [], $this->version, 'all' );
		}
	}

	/**
	 * Enqueues the scripts for the admin side of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		global $typenow;
		if ( 'post' === $typenow || 'page' === $typenow ) {
			wp_enqueue_script( 'story-lines-admin-script', plugin_dir_url( __FILE__ ) . 'js/story-lines-admin.min.js', [ 'jquery' ], $this->version, 'all' );
		}
	}

	/**
	 * Displays help text in the contextual menu.
	 *
	 * @since 2.0.0
	 *
	 * @param string    $contextual_help      The incoming text for contextual help.
	 * @param int       $screen_id               The id of the current screen.
	 * @param WP_Screen $screen            The current screen.
	 * @return string                      The new contextual help screen text.
	 */
	public function contextual_help( $contextual_help, $screen_id, $screen ) {
		if ( 'post' === $screen->id ) {
			$contextual_help = '<h2>' . __( 'Story Lines Help', 'story-lines' ) . '</h2>';
			$contextual_help .= '<ul>';
			$contextual_help .= '<li>' . __( 'Anchor Links', 'story-lines' ) . '<br />' . __( 'To create and add anchor links to the story lines, first wrap the area you want to jump to in a div or span and give the element an id (i.e. &lt;span id="your-id"&gt;). Then add the id you have given the section to the appropriate story line.', 'story-lines' ) . '</li>';
			$contextual_help .= '</ul>';
		}

		return $contextual_help;
	}

	/**
	 * Adds in the meta box for the story lines.
	 *
	 * @since 2.0.0
	 */
	public function add_meta_box() {
		add_meta_box( 'story-lines-meta', __( 'Add Story Lines', 'story-lines' ), [ $this, 'create_meta_box' ], ['post', 'page' ], 'normal', 'default' );
	}

	/**
	 * Loads in the custom meta box for the story lines.
	 *
	 * @since 2.0.0
	 */
	public function create_meta_box() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/story-lines-meta-box.php';
	}

	/**
	 * Saves the data in the meta box for the story lines.
	 *
	 * @since 2.0.0
	 *
	 * @param int $post_id      The ID of the post.
	 */
	public function meta_box_save( $post_id ) {
		$float_array = [
			'left'   => __( 'Left', 'story-lines' ),
			'center' => __( 'Center', 'story-lines' ),
			'right'  => __( 'Right', 'story-lines' ),
		];

		if ( ! isset( $_POST['story_lines_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['story_lines_meta_box_nonce'], 'story_lines_meta_box_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$old = get_post_meta( $post_id, 'story_lines_highlights', true );
		$new = array();

		$lines = $_POST['story_lines_highlight'];
		$anchor_ids = $_POST['story_lines_anchor_id'];

		$num = count( $lines );

		if ( isset( $_POST['story_lines_title'] ) ) {
			update_post_meta( $post_id, 'story_lines_title', sanitize_text_field( wp_filter_nohtml_kses( $_POST['story_lines_title'] ) ) );
		}

		if ( isset( $_POST['story_lines_size'] ) ) {
			update_post_meta( $post_id, 'story_lines_size', wp_filter_nohtml_kses( intval( $_POST['story_lines_size'] ) ) );
		}

		if ( $_POST['story_lines_float'] && array_key_exists( $_POST['story_lines_float'], $float_array ) ) {
			update_post_meta( $post_id, 'story_lines_float', wp_filter_nohtml_kses( $_POST['story_lines_float'] ) );
		}

		$title_bg_color = trim( $_POST['story_lines_title_background'] );
		$title_bg_color = wp_strip_all_tags( stripslashes( $title_bg_color ) );

		if ( true === $this->check_color( $title_bg_color ) ) {
			update_post_meta( $post_id, 'story_lines_title_background', $title_bg_color );
		}

		$main_bg_color = trim( $_POST['story_lines_main_background'] );
		$main_bg_color = wp_strip_all_tags( stripslashes( $main_bg_color ) );

		if ( true === $this->check_color( $main_bg_color ) ) {
			update_post_meta( $post_id, 'story_lines_main_background', $main_bg_color );
		}

		$title_color = trim( $_POST['story_lines_title_color'] );
		$title_color = wp_strip_all_tags( stripslashes( $title_color ) );

		if ( true === $this->check_color( $title_color ) ) {
			update_post_meta( $post_id, 'story_lines_title_color', $title_color );
		}

		$main_color = trim( $_POST['story_lines_main_color'] );
		$main_color = wp_strip_all_tags( stripslashes( $main_color ) );

		if ( true === $this->check_color( $main_color ) ) {
			update_post_meta( $post_id, 'story_lines_main_color', $main_color );
		}

		for ( $i = 0; $i < $num; $i++ ) {
			if ( isset( $lines[ $i ] ) ) {
				$new[ $i ]['story_lines_highlight'] = sanitize_text_field( wp_filter_nohtml_kses( $lines[ $i ] ) );
			}
			if ( isset( $anchor_ids[ $i ] ) ) {
				$new[ $i ]['story_lines_anchor_id'] = sanitize_text_field( wp_filter_nohtml_kses( $anchor_ids[ $i ] ) );
			}
		}

		if ( ! empty( $new ) && $new !== $old ) {
			update_post_meta( $post_id, 'story_lines_highlights', $new );
		} elseif ( empty( $new ) && $old ) {
			delete_post_meta( $post_id, 'story_lines_highlights', $old );
		}
	}

	/**
	 * Checks to make sure the value is a color in hexidecimal format.
	 *
	 * @since 2.0.0
	 *
	 * @param string $value      The color value given.
	 * @return bool              Whether or not the value is a color code in hexidecimal format.
	 */
	public function check_color( $value ) {
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Adds in the TinyMCE Editor buttons.
	 *
	 * @since 2.0.0
	 */
	public function story_lines_buttons() {
		add_filter( 'mce_external_plugins', [ $this, 'add_buttons' ] );
		add_filter( 'mce_buttons', [ $this, 'register_buttons' ] );
	}

	/**
	 * Adds in the JavaScript for the button.
	 *
	 * @since 2.0.0
	 *
	 * @param array $plugin_array      The incoming array of plugins.
	 */
	public function add_buttons( $plugin_array ) {
		$plugin_array['story_lines'] = plugin_dir_url(__FILE__) . 'js/story-lines-admin-button.min.js';
		return $plugin_array;
	}

	/**
	 * Adds in the button for the TinyMCY editor.
	 *
	 * @since 2.0.0
	 *
	 * @param array $buttons      The incoming array of buttons.
	 */
	public function register_buttons( $buttons ) {
		array_push( $buttons, 'story_lines' );
		return $buttons;
	}

	/**
	 * Loads the block editor scripts and styles.
	 *
	 * @since 2.0.0
	 */
	public function blocks_editor_scripts() {
		$block_path = '../public/js/editor.blocks.js';

		wp_enqueue_style(
			'read-more-about-blocks-editor-css',
			plugin_dir_url( __FILE__ ) . 'css/blocks.editor.css'
		);

		wp_enqueue_script(
			'story-lines-blocks-js',
			plugins_url( $block_path, __FILE__ ),
			[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api', 'wp-editor' ],
			filemtime( plugin_dir_path(__FILE__) . $block_path )
		);

		wp_localize_script(
			'story-lines-blocks-js',
			'story_lines_globals',
			[
				'rest_url'  => esc_url( rest_url() ),
				'nonce'     => wp_create_nonce( 'wp_rest' ),
			]
		);
	}

	/**
	 * Loads the front-end styles for the block.
	 *
	 * @since 2.0.0
	 */
	public function block_scripts() {
		$style_path = '../public/css/block.min.css';
		wp_enqueue_style(
			'story-lines-block-css',
			plugins_url( $style_path, __FILE__ )
		);
	}

	/**
	 * Checks to make sure Gutenberg is active or the WP version is greater than 5.0.
	 *
	 * @since 2.0.0
	 */
	public function check_gutenberg() {
		if ( ! function_exists( 'register_block_type' ) ) {
			// Block editor is not available.
			return;
		}

		add_action( 'enqueue_block_assets', [ $this, 'block_scripts' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'blocks_editor_scripts' ] );
	}

}

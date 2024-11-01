<?php
/**
 * Holds all of the admin side functions.
 *
 * PHP version 7.3
 *
 * @link       https://jacobmartella.com
 * @since      2.0.0
 *
 * @package    Story_Lines
 * @subpackage Story_Lines/admin
 */

namespace Story_Lines;

use WP_Query;

/**
 * Runs the admin side.
 *
 * This class defines all code necessary to run on the admin side of the plugin.
 *
 * @since      2.0.0
 * @package    Story_Lines
 * @subpackage Story_Lines/admin
 */
class Story_Lines_Blocks {

	/**
	 * Version of the plugin.
	 *
	 * @since 2.0.0
	 * @var string $version Description.
	 */
	private $version;


	/**
	 * Builds the Story_Lines object.
	 *
	 * @since 2.0.0
	 *
	 * @param string $version Version of the plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
	}

	public function create_blocks() {
		register_block_type(
			__DIR__ . '/build/story-lines'
		);
	}

	/**
	 * Render callback function.
	 *
	 * @param array    $attributes The block attributes.
	 * @param string   $content    The block content.
	 * @param WP_Block $block      Block instance.
	 *
	 * @return string The rendered output.
	 */
	public function render_story_lines_block( $attributes, $content, $block ) {
		ob_start();
		require plugin_dir_path( __FILE__ ) . 'build/story-lines/template.php';
		return ob_get_clean();
	}

}

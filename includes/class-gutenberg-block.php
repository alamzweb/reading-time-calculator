<?php
namespace RTC\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gutenberg_Block {

	public function __construct() {
		add_action( 'init', [ $this, 'register_gutenberg_block' ] );
	}

	public function register_gutenberg_block(): void {
		wp_register_script(
			'rtc-block-script',
			RTC_PLUGIN_URL . 'assets/js/block.js',
			[ 'wp-blocks', 'wp-element', 'wp-server-side-render' ],
			RTC_VERSION
		);

		register_block_type( 'reading-time-calculator/display-block', [
			'editor_script'   => 'rtc-block-script',
			'render_callback' => [ $this, 'render_block' ],
		] );
	}

	public function render_block( array $attributes ): string {
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return '<p>' . esc_html__( 'Reading time not available in preview.', 'reading-time-calculator' ) . '</p>';
		}
		$reading_time_obj = new Reading_Time();
		return $reading_time_obj->get_reading_time_markup( $post_id );
	}
}
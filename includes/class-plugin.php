<?php
namespace RTC\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	private static ?Plugin $instance = null;

	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->init_components();
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_styles' ] );
		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

	private function init_components(): void {
		new Settings();
		new Reading_Time();
		new Shortcode();
		new Gutenberg_Block();
	}

	public function enqueue_frontend_styles(): void {
		wp_enqueue_style(
			'rtc-frontend-style',
			RTC_PLUGIN_URL . 'assets/css/style.css',
			[],
			RTC_VERSION
		);
	}

	public function load_textdomain(): void {
		load_plugin_textdomain( 'reading-time-calculator', false, dirname( RTC_PLUGIN_BASENAME ) . '/languages' );
	}
}
<?php

/**
 * Singleton pattern.
 */
abstract class TaroSitemapSingleton {

	const OPTION_KEY = 'ts_esm_option_key';

	/**
	 * @var static[] $instances
	 */
	private static $instances = [];

	/**
	 * Constructor.
	 */
	final protected function __construct() {
		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	protected function init() {
		// Do something.
	}

	/**
	 * Save option value.
	 *
	 * @return string
	 */
	public function get_option_value() {
		return (string) get_option( static::OPTION_KEY, '' );
	}

	/**
	 * Sitemap URL.
	 *
	 * @return string
	 */
	public function sitemap_url() {
		return home_url( 'exclusive-sitemap.xml' );
	}

	/**
	 *
	 *
	 * @return static
	 */
	public static function get_instance() {
		$class_name = get_called_class();
		if ( ! isset( self::$instances[ $class_name ] ) ) {
			self::$instances[ $class_name ] = new $class_name();
		}
		return self::$instances[ $class_name ];
	}
}

<?php

/**
 * Render sitemap.
 */
class TaroSitemapRender extends TaroSitemapSingleton {

	/**
	 * {@inheritDoc}
	 */
	protected function init() {
		add_action( 'init', [ $this, 'add_rewrite_rule' ] );
		add_action( 'do_feed_exclusive-sitemap', [ $this, 'render' ] );
		add_filter( 'robots_txt', [ $this, 'filter_robots_txt' ] );
	}

	/**
	 * Add rewrite rule.
	 *
	 * @return void
	 */
	public function add_rewrite_rule() {
		add_rewrite_rule( '^exclusive-sitemap.xml$', 'index.php?feed=exclusive-sitemap', 'top' );
	}

	/**
	 * Render sitemap.
	 *
	 * @return void
	 */
	public function render() {
		$urls = $this->get_urls();
		if ( empty( $urls ) ) {
			// If URL not found, 404
			wp_die( 'No sitemap data.', '', [
				'status'    => 404,
				'response'  => '404',
				'back_link' => true,
			] );
		}
		// Render sitemap.
		header( 'Content-Type: application/xml; charset=' . get_option( 'blog_charset' ), true );
		echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?>' . PHP_EOL;
		?>
		<urlset
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
			xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
			<?php foreach ( $urls as $url ) : ?>
				<url>
					<loc><?php echo $url; ?></loc>
				</url>
			<?php endforeach; ?>
			</urlset>
		<?php
		exit;
	}

	/**
	 * Filter robots.txt for sitemap.
	 *
	 * @param string $robots
	 * @return string
	 */
	public function filter_robots_txt( $robots ) {
		$urls = $this->get_urls();
		if ( empty( $urls ) ) {
			return $robots;
		}
		return $robots . sprintf( "\nSitemap: %s\n", $this->sitemap_url() );
	}

	/**
	 * Get URLs for sitemap.
	 *
	 * @return string[]
	 */
	public function get_urls() {
		$urls = array_values( array_filter( array_map( function ( $url ) {
			return esc_url( $url );
		}, preg_split( "@(\r\n|\r|\n)@u", $this->get_option_value() ) ) ) );
		return apply_filters( 'ts_esm_urls', $urls );
	}
}

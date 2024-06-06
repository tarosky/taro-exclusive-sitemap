<?php

/**
 * Admin screen for plugin.

 */
class TaroSitemapAdmin extends TaroSitemapSingleton {

	private $slug = 'taro-exclusive-sitemap';

	/**
	 * {@inheritDoc}
	 */
	protected function init() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );
		add_action( 'wp_ajax_' . $this->slug, [ $this, 'admin_ajax' ] );
	}

	/**
	 * Register menu.
	 *
	 * @return void
	 */
	public function add_menu() {
		add_submenu_page( 'tools.php', __( 'Exclusive Sitemap', 'ts-esm' ), __( 'Exclusive Sitemap', 'ts-esm' ), 'edit_posts', $this->slug, [ $this, 'render' ] );
	}

	/**
	 * Save option page.
	 *
	 * @return void
	 */
	public function admin_ajax() {
		try {
			if ( ! check_admin_referer( $this->slug ) ) {
				throw new \Exception( __( 'Invalid nonce.', 'ts-esm' ), 400 );
			}
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new \Exception( __( 'You have no permission.', 'ts-esm' ), 400 );
			}
			update_option( self::OPTION_KEY, filter_input( INPUT_POST, self::OPTION_KEY ) );
			wp_safe_redirect( add_query_arg( [
				'page'    => $this->slug,
			], admin_url( 'tools.php' ) ) );
			exit;
		} catch ( \Exception $e ) {
			wp_die( $e->getMessage(), get_status_header_desc( $e->getCode() ), [
				'status'    => $e->getCode(),
				'response'  => $e->getCode(),
				'back_link' => true,
			] );
		}
	}

	/**
	 * Render setting screen.
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Exclusive Sitemap', 'ts-esm' ); ?></h1>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
				<input type="hidden" name="action" value="<?php echo esc_attr( $this->slug ); ?>" />
				<?php
				wp_nonce_field( $this->slug );
				do_settings_sections( $this->slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register admin settings.
	 *
	 * @return void
	 */
	public function admin_init() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		// Section.
		add_settings_section( 'ts_esm_section', __( 'Settings', 'ts-esm' ), function () {
		}, $this->slug );
		// Fields.
		add_settings_field( static::OPTION_KEY, __( 'URLs', 'ts-esm' ), function () {
			$placeholders = implode( "\n", [
				'e.g.',
				'https://example.com/article/123/?fbcid=1234567890',
				'https://xample.com/article/1234/plugin-not-used/',
			] );
			printf(
				'<textarea name="%s" rows="20" style="%s" placeholder="%s">%s</textarea>',
				esc_attr( static::OPTION_KEY ),
				esc_attr( 'width: 100%; box-sizing: border-box;' ),
				esc_attr( $placeholders ),
				esc_textarea( $this->get_option_value() )
			);
			printf(
				'<p class="description">%s</p>',
				esc_html__( 'Enter URLs to include in the exclusive sitemap.', 'ts-esm' )
			);
		}, $this->slug, 'ts_esm_section' );
		// URL check fields.
		add_settings_field( static::OPTION_KEY . '_url', __( 'Sitemap URL', 'ts-esm' ), function () {
			printf(
				'<input type="url" value="%s" readonly style="%s" />',
				esc_url( $this->sitemap_url() ),
				esc_attr( 'width: 100%; box-sizing: border-box;' ),
			);
			printf(
				'<p class="description">%s &raquo; %s</p>',
				esc_html__( 'Register URL above on Google Search Console.', 'ts-esm' ),
				sprintf( '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', esc_url( $this->sitemap_url() ), esc_html__( 'Preview', 'ts-esm' ) )
			);
		}, $this->slug, 'ts_esm_section' );
	}
}

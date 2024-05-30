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
	}

	/**
	 * Register menu.
	 *
	 * @return void
	 */
	public function add_menu() {
		add_submenu_page( 'tools.php', __( 'Exclusive Sitemap', 'ts-esm' ), __( 'Exclusive Sitemap', 'ts-esm' ), 'edit_others_posts', $this->slug, [ $this, 'render' ] );
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
			<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
				<?php
				settings_fields( $this->slug );
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
		register_setting( $this->slug, static::OPTION_KEY );
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

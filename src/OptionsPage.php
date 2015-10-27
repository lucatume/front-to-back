<?php
namespace tad\FrontToBack;

use tad\FrontToBack\Templates\Filesystem;
use tad\FrontToBack\Templates\MasterChecker;

class OptionsPage {

	private $key = 'ftb_options';

	private $metabox_id = 'ftb_option_metabox';

	protected $title = '';

	protected $options_page = '';

	public function __construct() {
		$this->title = __( 'Front to Back settings', 'ftb' );
	}

	public function hooks() {
		add_action( 'admin_init', array(
			$this, 'register_setting'
		) );
		add_action( 'admin_menu', array(
			$this, 'add_options_page'
		) );
		add_action( 'cmb2_admin_init', array(
			$this, 'add_options_page_metabox'
		) );
		add_filter( "cmb2_override_option_save_{$this->key}", array( $this, 'require_credentials' ), 10, 2 );
	}

	public function require_credentials( $override, $options ) {
		if ( ! empty( $options['templates_folder'] ) ) {
			$filesystem = new Filesystem( $options['templates_folder'] );
			$filesystem->initialize_wp_filesystem();
		}

		return $override;
	}

	public function register_setting() {
		register_setting( $this->key, $this->key );
	}

	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array(
			$this, 'admin_page_display'
		) );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array(
			'CMB2_hookup', 'enqueue_cmb_css'
		) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 *
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 *
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array(
			$this, 'settings_notices'
		), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id' => $this->metabox_id, 'hookup' => false, 'cmb_styles' => false, 'show_on' => array(
				// These are important, don't remove
				'key' => 'options-page', 'value' => array( $this->key, )
			),
		) );

		// Set our CMB2 fields

		$cmb->add_field( array(
			'name'            => __( 'Templates folder', 'ftb' ),
			'desc'            => sprintf( __( 'The absolute path path to the templates folder.', 'ftb' ), ABSPATH ),
			'id'              => 'templates_folder', 'type' => 'text',
			'default'         => ftb()->get( 'templates/default-folder' ),
			'sanitization_cb' => array( $this, 'sanitize_templates_path' )
		) );
	}

	public function sanitize_templates_path( $value ) {
		return ! empty( $value ) ? trailingslashit( $value ) : ftb()->get( 'templates/default-folder' );
	}

	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		/** @var MasterChecker $checker */
		$checker          = ftb()->get( 'master-template-checker' );
		$templates_folder = ftb_get_option( 'templates_folder' );
		$master_ok        = $checker->check( $templates_folder );

		if ( $master_ok ) {
			add_settings_error( $this->key . '-notices', '', __( 'Front to Back settings updated.', 'ftb' ), 'updated' );
		} else {
			$message = $checker->get_notice_message( $templates_folder );
			add_settings_error( $this->key . '-notices', '', $message, 'error' );
		}
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * @param $field
	 *
	 * @return mixed
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array(
			'key', 'metabox_id', 'title', 'options_page'
		), true ) ) {
			return $this->{$field};
		}

		throw new \InvalidArgumentException( 'Invalid property: ' . $field );
	}
}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://itjustdoes.co.uk
 * @since      1.0.0
 *
 * @package    Rss_Vsp
 * @subpackage Rss_Vsp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rss_Vsp
 * @subpackage Rss_Vsp/admin
 * @author     It Just Does <richard@itjustdoes.co.uk>
 */
class Rss_Vsp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Unique(ish) identifier for prepending to funciton names
	 * 
	 * @since	1.0.0
	 * @access	private
	 * @var		string	$unique_name	The 'name' of this plugin
	 */
	private $unique_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->unique_name = "rss_vsp";
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rss_Vsp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rss_Vsp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rss-vsp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rss_Vsp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rss_Vsp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rss-vsp-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'RSS Vertical Scroll Post Settings', 'rss-vsp' ),
			__( 'RSS VSP', 'rss-vsp' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);

	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/rss-vsp-admin-display.php';
	}

	/**
	 * Register all related settings of this plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {

		/* Sections */
		add_settings_section(
			$this->unique_name . '_general',                            // tag ID (CSS) - slug name
			__( 'General', $this->plugin_name ),                        // title of the section
			array( $this, $this->unique_name . '_general_render' ),     // callback to render
			$this->plugin_name                                          // page
		);

        /* Fields */
        /* Category selection */
        add_settings_field(
            $this->unique_name . '_category',                           // tag id (CSS)
            __( 'Category of posts to display', $this->plugin_name ),   // title of the field
            array( $this, $this->unique_name . '_category_render' ),    // callback to render
            $this->plugin_name,                                         // settings page
            $this->unique_name . '_general',                            // section
            array( 'label_for' => $this->unique_name . '_category')     // arguments to pass to the callback
        );

        register_setting(
            $this->plugin_name,
            $this->unique_name . '_category'
        );
        /* Post content to display */
        $this->build_field( array (
            'field_var'     => $this->unique_nane . '_content_paragraph',
            'label_text'    => 'Paragraph',
            'field_title'   => 'Contents to display',
            'type'          => 'checkbox',
        ));

        $this->build_field( array (
            'field_var'     => $this->unique_nane . '_content_header',
            'label_text'    => 'Header',
            'type'          => 'checkbox',
        ));
        
        $this->build_field( array (
            'field_var'     => $this->unique_nane . '_content_image',
            'label_text'    => 'Image',
            'type'          => 'checkbox',
        ));
        
        /* N most recent posts */
        $this->build_field( array (
            'field_var'     => $this->unique_nane . '_mostrecent',
            'label_text'    => '',
            'field_title'   => 'Number most recent posts to display',
            'type'          => 'wholenumber',
        ));
        /* Lines at once to display */
        $this->build_field( array (
            'field_var'     => $this->unique_nane . '_lines',
            'label_text'    => '',
            'field_title'   => 'Number lines displayed',
            'type'          => 'wholenumber',
        ));
        /* Scrolling speed in seconds (what does this mean: update rate per line?) */
        $this->build_field( array (
            'field_var'     => $this->unique_nane . '_speed',
            'label_text'    => ' seconds',
            'field_title'   => 'Scrolling speed',
            'type'          => 'wholenumber',
        ));

    }

    /**
     * Build a field in the admin page, also register the field
     */
    private function build_field( $args ) {
        if ( isset( $args[ 'type' ] ) ) {
            $type = $args[ 'type' ];
            if ( !in_array( $type , array( 
                'checkbox', 
                'wholenumber',
                ) ) ) {
                echo 'No type ' . $type . ' in build_field';
                return;
            }
        }
        if ( isset( $args[ 'field_var' ] ) ) {
            $field_var = $args[ 'field_var' ];
        } else {
            echo 'No field variable in arguments';
            return;
        }
        if ( isset( $args[ 'label_text' ] ) ) {
            $label_text = $args[ 'label_text' ];
        } else {
            $label_text = '';
        }
        if ( isset( $args[ 'field_title' ] ) ) {
            $field_title = $args[ 'field_title' ];
        } else {
            $field_title = '';
        }

        add_settings_field(
            $field_var,
            __( $field_title, $this->plugin_name ),
            array( $this, $this->unique_name . '_' . $type . '_render' ),
            $this->plugin_name,
            $this->unique_name . '_general',
            array(
                'field_var'     => $field_var,
                'label_text'    => $label_text,
                'type'          => $type,
            )
        );

        register_setting(
            $this->plugin_name,
            $field_var
        );
    }

	/**
	 * Rendering the settings
	 */
	/** 
	 * Render the General section
	 * 
	 * @since   1.0.0
	 */
    public function rss_vsp_general_render() {
        echo '<p>' . __( 'Change the settings to suit.<br>Shortcode is [rss_vsp]', $this->plugin_name ) . '</p>';
    }

    /**
     * Render the category choice field
     * 
     * @since   1.0.0
     */
    public function rss_vsp_category_render() {
        wp_dropdown_categories(
            array(
                'hide_empty'   => 0,
                'name'         => $this->unique_name . '_category',
                'orderby'      => 'name',
                'selected'     => get_option( $this->unique_name . '_category' ),
                'hierarchical' => true,
            )
        );
    }

    /**
     * Render a checkbox field
     *
     * @since   1.0.0
     * @param   string  field_var       name of the checkbox associated with a label
     * @param   string  label           text for the label
     */
    public function rss_vsp_checkbox_render( $args ) {
        if ( isset( $args[ 'field_var' ] ) ) {
            $field_var = $args[ 'field_var' ];
        } else {
            echo 'No field variable in arguments';
            return;
        }
        if ( isset( $args[ 'label_text' ] ) ) {
            $label_text = $args[ 'label_text' ];
        } else {
            $label_text = '';
        }

        echo '<label for="' . $field_var . '">';
        echo '<input name="' . $field_var . '" type="checkbox" id="' . $field_var . '" value="1" ' . checked( '1', get_option( $field_var), $echo=false ) . '/>';
        echo $label_text  . '</label>';
    }

    /**
     * Render a whole-number field
     * 
     * @since   1.0.0
     * @param   string  field_var       name of the number field
     * @param   string  label           text for the label
     */

    public function rss_vsp_wholenumber_render( $args ) {
        if ( isset( $args[ 'field_var' ] ) ) {
            $field_var = $args[ 'field_var' ];
        } else {
            echo 'No field variable in arguments';
            return;
        }
        if ( isset( $args[ 'label_text' ] ) ) {
            $label_text = $args[ 'label_text' ];
        } else {
            $label_text = '';
        }

        echo '<label for="' . $field_var . '">';
        echo '<input name="' . $field_var . '" type="number" id="' . $field_var . '" min="0" step="1" value="'. get_option( $field_var) . '"/>';
        echo $label_text  . '</label>';
    }

	 /**
	  * Santizing the inputs before saving to the database
	  */

} /* class */

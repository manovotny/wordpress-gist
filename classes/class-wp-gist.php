<?php
/**
 * WP_Gist.
 *
 * @package     WP_Gist
 * @author      Michael Novotny <manovotny@gmail.com>
 * @license     GPL-3.0+
 * @link        https://github.com/manovotny/wp-gist
 * @copyright   2014 Michael Novotny
 */

/*
/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ CONTENTS /\/\/\/\/\/\/\/\/\/\/\/\/\/\//\/\/\/\/\

    1. Properties
    2. Constructor
    3. Actions
    4. Shortcodes

/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\//\/\/\/\/\/\/\/\/\/\
*/

class WP_Gist {

    /* Properties
    ---------------------------------------------------------------------------------- */

    /* Instance
    ---------------------------------------------- */

    /**
     * Instance of the WP_Gist class.
     *
     * @access      protected static
     * @var         WP_Gist
     *
     * @since       2.0.0
     * @version     1.0.0
     */
    protected static $instance = null;

    /**
     * Get accessor method for instance property.
     *
     * @return      WP_Gist  Instance of the WP_Gist class.
     *
     * @since       2.0.0
     * @version     1.0.0
     */
    public static function get_instance() {

        // Check if an instance has not been created yet.
        if ( null == self::$instance ) {

            // Set instance of class.
            self::$instance = new self;

        } // end if

        // Return instance.
        return self::$instance;

    } // end get_instance

    /* Slug
    ---------------------------------------------- */

    /**
     * Plugin unique identifier.
     *
     * @access      public
     * @var         string
     *
     * @since       2.0.0
     * @version     1.0.0
     */
    public $slug = 'wp-gist';

    /* Version
    ---------------------------------------------- */

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @access      protected
     * @var         string
     *
     * @since       2.0.0
     * @version     1.0.0
     */
    protected $version = '3.0.0';

    /* Constructor
    ---------------------------------------------------------------------------------- */
    /**
     * Initializes plugin.
     *
     * @since       1.0.0
     * @version     2.0.0
     */
    function __construct() {

        // Add shortcode hook.
        add_shortcode( 'wpgist', array( $this, 'wp_gist_shortcode' ) );

        // Register styles.
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_gist_styles' ), 1000 );

    } // end constructor

    /* Actions
    ---------------------------------------------------------------------------------- */

    /**
     * Registers plugin styles.
     *
     * @since       1.0.0
     * @version     2.0.0
     */
    public function wp_gist_styles() {

        // Plugin styles.
        wp_enqueue_style( $this->slug . '-style', plugins_url( $this->slug . '/css/style.min.css' ), false, $this->version );

    } // end wp_gist_styles

    /* Shortcodes
    ---------------------------------------------------------------------------------- */
    /**
     * @param       object  $attributes     Post attributes.
     * @param       string  $content        Post content.
     *
     * @return      string                  Expanded shortcode.
     *
     * @since       1.0.0
     * @version     2.0.0
     */
    function wp_gist_shortcode( $attributes, $content = null ) {

        // Extract shortcode attributes.
        extract(
            shortcode_atts(
                array(
                    'file' => '',
                    'id' => '',
                    'url' => ''
                ),
                $attributes
            )
        );

        // Check that we at least have a Gist or id.
        if ( empty( $id ) && empty( $url ) ) {

            // No Gist url or id.
            return '<!-- Gist url or id is required by [wpgist] shortcode -->';

            // Check if we can use Gist id.
        } else if ( ! empty( $id ) ) {

            // Set url based on id and append a .js file extension.
            $url = 'https://gist.github.com/' . $id . '.js';

            // Use Gist url.
        } else {

            // Append a .js file extension.
            $url .= '.js';

        } // end if / else

        // Check for specific file in the Gist.
        if ( ! empty( $file ) ) {

            // Append file to URL.
            $url .= '?file=' . $file;

        } // end if

        // Check if Autoptimize is activate & optimizing JS code & not look for scripts in <head> only
        if ( class_exists( 'autoptimizeCache' ) && get_option( 'autoptimize_js', false ) && ! get_option( 'autoptimize_js_justhead', false ) ) {

            return '<!--noptimize--><script src="' . $url . '"></script><!--/noptimize-->';

        } // end if

        // Construct Gist script and return.
        return '<script src="' . $url . '"></script>';

    } // end wp_gist_shortcode

} // end class

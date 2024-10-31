<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'myCred_Badge_Plus_Init' ) ):
class myCred_Badge_Plus_Init {

    protected static $_instance = NULL;

    public static $version = '1.0.0';

    /**
     * Setup Instance
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function get_instance() {

        if( is_null( self::$_instance ) ) {

            self::$_instance = new self();

        }

        return self::$_instance;
    }

    /**
     * Constructor
     * @since 1.0.0
     * @version 1.0.0
     */
    public function __construct() {

        require_once MYCRED_BADGE_PLUS_INCLUDES_DIR     . 'badge-plus-functions.php';
        register_activation_hook( MYCRED_BADGE_PLUS, array( $this, 'mycred_badge_plus_activate' ) );
        add_filter( 'mycred_load_modules', array( $this, 'mycred_load_badge_plus_addon' ), 10, 2  );

    }

    public function mycred_badge_plus_activate() { 
        mycred_register_badge_plus();
        mycred_register_badge_types();

        flush_rewrite_rules();
    }

    /**
     * Load Badge Module and files
     * @since 1.0.0
     * @version 1.0.0
     */
    public function mycred_load_badge_plus_addon( $modules, $point_types ) {

        if ( version_compare( myCRED_VERSION, 2.5, '>=' ) ) {

            require_once MYCRED_BADGE_PLUS_INCLUDES_DIR . 'badge-plus-module-class.php';

            $modules['solo']['badge-plus'] = new myCRED_Badge_Plus_Module();
            $modules['solo']['badge-plus']->load();

        } 
        else {

            add_action( 'admin_notices', array( $this, 'mycred_badge_plus_addon_notice' ) );

        }

        return $modules;
        
    }

    public function mycred_badge_plus_addon_notice() {

        echo '<div class="notice notice-error is-dismissible"><p>myCred Badge Plus requires myCred 2.5 or greater version.</p></div>';

    }

}
endif;

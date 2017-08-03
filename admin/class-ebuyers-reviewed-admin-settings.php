<?php
/*
 *
 * This generates the setting page in the admin.
 *
 */
class Ebuyers_Reviewed_Admin_Settings extends Ebuyers_Reviewed_Admin
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->options = get_option( 'ebuyers_reviewed_settings' );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
       add_menu_page( 'eBuyersReviewed', 'eBuyersReviewed', 'manage_options', 'ebuyers-reviewed-settings', array( $this, 'create_admin_page' ) );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'ebuyers_reviewed_settings' );
        ?>
        <div class="wrap">
            <h2>eBuyersReviewed Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'ebuyers_option_group' );   
                do_settings_sections( 'ebuyers-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'ebuyers_option_group', // Option group
            'ebuyers_reviewed_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'ebuyers-admin' // Page
        );  

        // Auth Token Field
        add_settings_field(
            'ebuyers_api_key', // ID
            'eBuyersReviewed API Key', // Title 
            array( $this, 'ebuyers_api_key_callback' ), // Callback
            'ebuyers-admin', // Page
            'setting_section_id' // Section
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['ebuyers_api_key'] ) )
            $new_input['ebuyers_api_key'] = sanitize_text_field( $input['ebuyers_api_key'] );
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your API key below';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function ebuyers_api_key_callback()
    {
        printf(
            '<input type="text" id="ebuyers_api_key" name="ebuyers_reviewed_settings[ebuyers_api_key]" value="%s" size="50"/>',
            isset( $this->options['ebuyers_api_key'] ) ? esc_attr( $this->options['ebuyers_api_key']) : ''
        );
        echo '<br/>';
        echo "<small>Find your API Key <a href='http://www.ebuyersreviewed.com/en/api_settings/API-settings.html' target='_blank'>here</a></small>";
    }

}
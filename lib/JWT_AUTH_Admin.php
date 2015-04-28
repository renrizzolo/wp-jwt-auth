<?php

class JWT_AUTH_Admin{
    public static function init(){
        add_action( 'admin_init', array(__CLASS__, 'init_admin'));
        add_action( 'admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue'));
    }

    public static function admin_enqueue(){
        if(!isset($_REQUEST['page']) || $_REQUEST['page'] != 'jwta')
            return;
/*
        wp_enqueue_media();
        wp_enqueue_script( 'wpa0_admin', WPA0_PLUGIN_URL . 'assets/js/admin.js', array('jquery'));
        wp_enqueue_style( 'wpa0_admin', WPA0_PLUGIN_URL . 'assets/css/settings.css');
        wp_enqueue_style('media');

        wp_localize_script( 'wpa0_admin', 'wpa0', array(
            'media_title' => __('Choose your icon', WPA0_LANG),
            'media_button' => __('Choose icon', WPA0_LANG)
        ));
*/
    }

    protected static function init_option_section($sectionName, $settings)
    {
        $lowerName = strtolower($sectionName);
        add_settings_section(
            "jwt_auth_{$lowerName}_settings_section",
            __($sectionName, WPA0_LANG),
            array(__CLASS__, "render_{$lowerName}_description"),
            JWT_AUTH_Options::OPTIONS_NAME
        );

        foreach ($settings as $setting)
        {
            add_settings_field(
                $setting['id'],
                __($setting['name'], WPA0_LANG),
                array(__CLASS__, $setting['function']),
                JWT_AUTH_Options::OPTIONS_NAME,
                "jwt_auth_{$lowerName}_settings_section",
                array('label_for' => $setting['id'])
            );
        }
    }

    public static function render_settings_description(){

    }

    public static function init_admin(){

/* ------------------------- BASIC ------------------------- */

        self::init_option_section('Settings', array(

            array('id' => 'jwt_auth_aud', 'name' => 'Aud', 'function' => 'render_aud'),
            array('id' => 'jwt_auth_secret', 'name' => 'Secret', 'function' => 'render_secret'),
            array('id' => 'jwt_auth_user_property', 'name' => 'User Property', 'function' => 'render_user_property'),
            array('id' => 'jwt_auth_jwt_attribute', 'name' => 'JWT Attribute', 'function' => 'render_jwt_attribute'),

        ));

        register_setting(JWT_AUTH_Options::OPTIONS_NAME, JWT_AUTH_Options::OPTIONS_NAME, array(__CLASS__, 'input_validator'));
    }


    public static function render_aud(){
        $v = JWT_AUTH_Options::get( 'aud' );
        echo '<input type="text" name="' . JWT_AUTH_Options::OPTIONS_NAME . '[aud]" id="jwt_auth_aud" value="' . esc_attr( $v ) . '"/>';
        echo '<br/><span class="description">' . __('JWT Audience (aud) represents the client id to which it is intended.', WPA0_LANG) . '</span>';
    }
    public static function render_secret(){
        $v = JWT_AUTH_Options::get( 'secret' );
        echo '<input type="text" name="' . JWT_AUTH_Options::OPTIONS_NAME . '[secret]" id="jwt_auth_secret" value="' . esc_attr( $v ) . '"/>';
        echo '<br/><span class="description">' . __('Secret value to verify the JWT signature.', WPA0_LANG) . '</span>';
    }
    public static function render_user_property(){
        $v = JWT_AUTH_Options::get( 'user_property' );
        echo '<input type="text" name="' . JWT_AUTH_Options::OPTIONS_NAME . '[user_property]" id="jwt_auth_user_property" value="' . esc_attr( $v ) . '"/>';
        echo '<br/><span class="description">' . __('WP User property which the plugin should look to find the related user.', WPA0_LANG) . '</span>';
    }
    public static function render_jwt_attribute(){
        $v = JWT_AUTH_Options::get( 'jwt_attribute' );
        echo '<input type="text" name="' . JWT_AUTH_Options::OPTIONS_NAME . '[jwt_attribute]" id="jwt_auth_jwt_attribute" value="' . esc_attr( $v ) . '"/>';
        echo '<br/><span class="description">' . __('JWT Attribute the plugin should use to match the users.', WPA0_LANG) . '</span>';
    }

    public static function render_settings_page(){
        include JWT_AUTH_PLUGIN_DIR . 'templates/settings.php';
    }

    protected static function add_validation_error($error)
    {
        add_settings_error(
            JWT_AUTH_Options::OPTIONS_NAME,
            JWT_AUTH_Options::OPTIONS_NAME,
            $error,
            'error'
        );
    }

    public static function input_validator( $input ){

        return $input;
    }
}
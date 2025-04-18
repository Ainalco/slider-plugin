<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function custom_slider_add_settings_menu() {
    add_options_page(
        __( 'Custom Slider Settings', 'custom-slider' ),
        __( 'Custom Slider', 'custom-slider' ),
        'manage_options',
        'custom-slider',
        'custom_slider_settings_page'
    );
}
add_action( 'admin_menu', 'custom_slider_add_settings_menu' );

function custom_slider_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Custom Slider Settings', 'custom-slider' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'custom_slider_settings' );
            do_settings_sections( 'custom-slider' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function custom_slider_register_settings() {
    register_setting(
        'custom_slider_settings',
        'custom_slider_type',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'custom_slider_sanitize_type',
            'default'           => 'post',
        )
    );

    register_setting(
        'custom_slider_settings',
        'custom_slider_count',
        array(
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'default'           => 5,
        )
    );

    add_settings_section(
        'custom_slider_main',
        __( 'Slider Options', 'custom-slider' ),
        '__return_false',
        'custom-slider'
    );

    add_settings_field(
        'custom_slider_type',
        __( 'Select Post Type', 'custom-slider' ),
        'custom_slider_type_field',
        'custom-slider',
        'custom_slider_main'
    );

    add_settings_field(
        'custom_slider_count',
        __( 'Number of Items', 'custom-slider' ),
        'custom_slider_count_field',
        'custom-slider',
        'custom_slider_main'
    );
}
add_action( 'admin_init', 'custom_slider_register_settings' );

// Sanitizer for type
function custom_slider_sanitize_type( $input ) {
    $valid = array( 'post', 'page' );
    return in_array( $input, $valid, true ) ? $input : 'post';
}

// Field Renderers
function custom_slider_type_field() {
    $value = get_option( 'custom_slider_type', 'post' );
    ?>
    <select name="custom_slider_type">
        <option value="post" <?php selected( $value, 'post' ); ?>><?php esc_html_e( 'Post', 'custom-slider' ); ?></option>
        <option value="page" <?php selected( $value, 'page' ); ?>><?php esc_html_e( 'Page', 'custom-slider' ); ?></option>
    </select>
    <?php
}

function custom_slider_count_field() {
    $value = absint( get_option( 'custom_slider_count', 5 ) );
    ?>
    <input type="number" name="custom_slider_count" value="<?php echo esc_attr( $value ); ?>" min="1" max="20" />
    <?php
}

<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class acf_field_visual_select extends acf_field_radio {

  function initialize() {
    $this->name = 'visual_select';
    $this->label = __("Visual Select",'acf');
    $this->category = 'choice';
    $this->defaults = array(
      'layout' => 'vertical',
      'choices' => array(),
      'default_value' => '',
      'return_format' => 'value',
    );
  }

  //

  function __construct( $settings ) {
    $this->settings = $settings;

    parent::__construct();
  }


  function render_field( $field ) {
    $i = 0;

    $output = '';

    $ul_attrs = array(
      'class' => 'acf-radio-list acf-bl acf-visual_select',
    );

    $ul['class'] .= ' ' . $field['class'];

    $checked = '';
    $value = strval( $field['value'] );

    if ( isset( $field['choices'][ $value ] ) ) {
      $checked = $value;
    } else {
      $checked = key( $field['choices'] );
    }

    $checked = strval( $checked );

    if ( empty( $field['choices'] ) ) {
      return;
    }

    $output .= acf_get_hidden_input( array( 'name' => $field['name'] ) );

    $output .= '<ul ' . acf_esc_attr($ul_attrs) . '>';

    foreach( $field['choices'] as $value => $label ) {
      $value = strval( $value );
      $class = '';

      $split = array();
      preg_match( '/\[([^\]]*)\]/', $label, $split );

      if ( ! empty( $split ) ) {
        $image = $split[1];
        $label = trim( str_ireplace( $split[0], '', $label ) );
      }

      $i++;

      $atts = array(
        'type' => 'radio',
        'id' => $field['id'],
        'name' => $field['name'],
        'value' => $value
      );

      if ( $value === $checked ) {
        $atts['checked'] = 'checked';
        $class = ' class="selected"';
      }

      if ( isset( $field['disabled'] ) && acf_in_array( $value, $field['disabled'] ) ) {
        $atts['disabled'] = 'disabled';
      }

      if ( $i > 1 ) {
        $atts['id'] .= '-' . $value;
      }

      $output .= '<li><label' . $class . '>';
      $output .= '<input ' . acf_esc_attr( $atts ) . '/>';

      if ( ! empty( $image ) ) {
        $find = array(
          'theme_uri/',
        );
        $replace = array(
          get_stylesheet_directory_uri() . '/',
        );

        $img_atts = array(
          'src' => str_ireplace( $find, $replace, $image ),
          'alt' => $label,
        );

        $output .= '<span class="thumb"><img ' . acf_esc_attr( $img_atts ) . ' /></span>';
      }

      $output .= '<span class="screen-reader-text">' . $label . '</span>';
      $output .= '</label></li>';
    }

    $output .= '</ul>';

    echo $output;
  }


  function input_admin_enqueue_scripts() {
    // vars
    $url = $this->settings['url'];
    $version = $this->settings['version'];

    // register & include CSS
    wp_register_style('acf_visual_select-css', "{$url}assets/css/input.css", array('acf-input'), $version);
    wp_enqueue_style('acf_visual_select-css');
  }


  function render_field_settings( $field ) {
    $field['choices'] = acf_encode_choices( $field['choices'] );

    acf_render_field_setting( $field, array(
      'label' => __('Choices','acf'),
      'instructions' => __('Enter each choice on a new line using the following format:.','acf') . '<br /><br />' . __('option1 : Option 1 [theme_uri/path/to/image_1.jpg]','acf'),
      'type' => 'textarea',
      'name' => 'choices',
    ));

    // default_value
    acf_render_field_setting( $field, array(
      'label' => __('Default Value','acf'),
      'instructions' => __('Appears when creating a new post','acf'),
      'type' => 'text',
      'name' => 'default_value',
    ));
  }


}

new acf_field_visual_select( $this->settings );
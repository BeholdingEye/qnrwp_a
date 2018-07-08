
<?php

defined('ABSPATH') || exit;

/**
 * Customizer class (a singleton) TODO
 * 
 * NOT USED because the Color Control does not feature alpha. Child theme
 * overrides should be used instead
 * 
 */
class QNRWP_Customizer {
    
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Filter and action hooks TODO
   */
  private function hooks() {
    add_action('customize_register', array($this, 'customize_register'));
    //add_action('wp_head', array($this, 'customize_css'));
  }
  
  
  /**
   * Manages our Customizer functionality
   */
  public function customize_register($wp_customize) {
    
    // Add settings
    //$wp_customize->add_setting('qnrwp_customizer_header_fixed' , array( // TODO
      //'default'           => 0,
      //'type'              => 'option', // 'option'/'theme_mod'
      //'capability'        => 'edit_theme_options',
      //'transport'         => 'refresh',
      //'sanitize_callback' => '',
    //));
    $wp_customize->add_setting('qnrwp_customizer_color_text' , array(
      'default'           => '#000000',
      'type'              => 'theme_mod', // 'option'/'theme_mod'
      'capability'        => 'edit_theme_options',
      'transport'         => 'refresh',
      'sanitize_callback' => '',
    ));
    
    //// Add sections TODO
    //$wp_customize->add_section('qnrwp_customizer_header' , array(
      //'title'       => __('QNRWP Header Options', 'qnrwp'),
      //'priority'    => 30,
      //'capability'  => 'edit_theme_options',
      //'description' => __('Allows you to customize the header.', 'qnrwp'),
    //));
    
    // Add controls to sections for settings
    //$wp_customize->add_control( // TODO
      //'qnrwp_customizer_header_fixed_control', 
      //array(
        //'label'       => __('Header fixed', 'qnrwp'),
        //'type'        => 'checkbox',
        //'section'     => 'qnrwp_customizer_header',
        //'settings'    => 'qnrwp_customizer_header_fixed',
        //'priority'    => 10,
        //'description' => __('Fix header.', 'qnrwp'),
      //)
    //);
    $wp_customize->add_control(new WP_Customize_Color_Control(
      $wp_customize,
      'qnrwp_customizer_color_text_control', 
      array(
        'label'       => __('Text Color', 'qnrwp'),
        'section'     => 'colors',
        'settings'    => 'qnrwp_customizer_color_text',
      )
    ));

  }
  
  
  /**
   * Outputs our CSS into header NOT USED
   */
  public function customize_css() {
    // Pass default value in CSS, to be changed by Customizer
    ?>
      <style type="text/css">
        h1 {
          color: <?php echo get_theme_mod('header_color', '#000000'); ?>;
        }
      </style>
    <?php
  }

} // End QNRWP_Customizer

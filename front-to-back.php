<?php

/**
 * Plugin Name: Front to Back
 * Plugin URI: http://theAverageDev.com
 * Description: Easy page templating for developers.
 * Version: 1.0
 * Author: theAverageDev
 * Author URI: http://theAverageDev.com
 * License: GPL 2.0
 */

include "vendor/autoload_52.php";

$page_locator = new FTB_Locators_Page();

$config_id = 'front-to-back-example';

Kirki::add_config($config_id, array(
    'capability' => 'edit_theme_options',
    'option_type' => 'theme_mod',
));

Kirki::add_panel('ftb-page-about_us-panel-customizations', array(
    'title' => 'Page customization',
    'active_callback' => array($page_locator, 'is_about_us'),
    'priority' => 150,
));

Kirki::add_section('ftb-page-about_us-section-content', array(
    'title' => 'Content',
    'panel' => 'ftb-page-about_us-panel-customizations',
));

Kirki::add_field($config_id, array(
    'settings' => 'ftb-page-about_us-picture_1',
    'label' => 'First picture',
    'section' => 'ftb-page-about_us-section-content',
    'type' => 'image',
));

Kirki::add_field($config_id, array(
    'settings' => 'ftb-page-about_us-picture_2',
    'label' => 'Second picture',
    'section' => 'ftb-page-about_us-section-content',
    'type' => 'image',
));

Kirki::add_field($config_id, array(
    'settings' => 'ftb-page-about_us-title',
    'label' => 'Title',
    'section' => 'ftb-page-about_us-section-content',
    'type' => 'text',
    'default' => 'About us',
));

Kirki::add_field($config_id, array(
    'settings' => 'ftb-page-about_us-content',
    'label' => 'Content',
    'section' => 'ftb-page-about_us-section-content',
    'type' => 'textarea',
    'default' => 'We are skilled',
));

add_action('customize_preview_init', 'ftb_add_about_us_page_filters');
function ftb_add_about_us_page_filters()
{
    $about_us_page = new FTB_Pages_AboutUs(new FTB_Locators_Page());
    add_filter('the_title', array($about_us_page, 'filter_the_title'), 1, 2);
    add_filter('the_content', array($about_us_page, 'filter_the_content'), 1, 2);
    add_action('customize_save_after', array($about_us_page, 'on_customize_after_save'), 10, 10);
}

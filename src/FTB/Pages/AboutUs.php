<?php

class FTB_Pages_AboutUs
{
    /**
     * @var FTB_Locators_Page
     */
    private $page_locator;

    public function __construct(FTB_Locators_Page $page_locator)
    {
        $this->page_locator = $page_locator;
    }


    public function filter_the_title($title, $post_id)
    {
        if ($post_id == $this->page_locator->get_about_us()->ID) {
            return get_theme_mod('ftb-page-about_us-title', 'About us');
        }

        return $title;
    }

    public function filter_the_content($content, $post_id = null)
    {
        global $post;
        if ($post && $post->ID == $this->page_locator->get_about_us()->ID) {
            return get_theme_mod('ftb-page-about_us-content', 'We are skilled');
        }

        return $content;
    }

    public function on_customize_after_save(WP_Customize_Manager $wp_customize)
    {
        $title = $wp_customize->get_setting('ftb-page-about_us-title');
        $content = $wp_customize->get_setting('ftb-page-about_us-content');

        $post_id = $this->page_locator->update_about_us(array(
            'post_title' => $title->value(),
            'post_content' => $content->value(),
        ));
    }
}
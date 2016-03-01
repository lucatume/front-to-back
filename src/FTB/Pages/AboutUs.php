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

    public function filter_get_post_metadata($value, $object_id, $meta_key)
    {
        $post = $this->page_locator->get_about_us();
        if ($post && $object_id == $post->ID) {
            switch ($meta_key) {
                case '_featured_image_caption':
                    $value = get_theme_mod('ftb-page-about_us-featured_image_caption', '');
                    break;
                case '_thumbnail_id':
                    $featured_image_url = get_theme_mod('ftb-page-about_us-featured_image', '');
                    $value = $this->get_attachment_id_from_url($featured_image_url);
                    break;
                default:
                    break;
            }
        }

        return $value;
    }

    public function on_customize_save_after(WP_Customize_Manager $wp_customize)
    {
        $title = $wp_customize->get_setting('ftb-page-about_us-title');
        $content = $wp_customize->get_setting('ftb-page-about_us-content');
        $featured_image = $wp_customize->get_setting('ftb-page-about_us-featured_image');
        $featured_image_caption = $wp_customize->get_setting('ftb-page-about_us-featured_image_caption');

        $featured_image_ID = $this->get_attachment_id_from_url($featured_image->value());

        $post_id = $this->page_locator->update_about_us(array(
            'post_title' => $title->value(),
            'post_content' => $content->value(),
            'meta_input' => array(
                '_thumbnail_id' => $featured_image_ID,
                '_featured_image_caption' => $featured_image_caption->value(),
            )
        ));
    }

    private function get_attachment_id_from_url($featured_image)
    {
        /** @var \wpdb $wpdb */
        global $wpdb;

        $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $featured_image));

        return empty($id) ? false : $id;
    }
}
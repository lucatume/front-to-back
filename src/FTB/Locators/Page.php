<?php

class FTB_Locators_Page
{
    protected $cache = array();

    public function __call($name, array $args = array())
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        // get the page snake_case post name
        $matches = array();
        preg_match('/(is|get|update)_([_A-Za-z09]*)/', $name, $matches);

        // if we have a match on the is_*_page format
        if (isset($matches[1]) && isset($matches[2])) {
            $request = $matches[1];
            $page = $matches[2];

            // make the post_name a URL friendly post-name
            $page_slug = str_replace('_', '-', $page);

            // look for the page in the DB
            $found = get_page_by_path($page_slug, OBJECT, 'page');

            // not found... false
            if (empty($found)) {
                return false;
            }

            $out = false;
            switch ($request) {
                case 'is':
                    // found and same page we are looking at?
                    $queried_post = $this->get_queried_post();
                    $out = $queried_post && $found->ID == $queried_post->ID;
                    break;
                case 'get':
                    $out = $found;
                    break;
                case'update':
                    return wp_update_post(array_merge(array('ID' => $found->ID), $args[0]));
                default:
                    $out = false;
                    break;
            }
        }

        $this->cache[$name] = $out;
        return $out;
    }

    private function get_queried_post()
    {
        global $wp_query;
        $posts = $wp_query->get_posts();

        // nothing to match against, bail
        if (empty($posts)) {
            return false;
        }

        // more than one post? Not a page for sure.
        if (count($posts) !== 1) {
            return false;
        }

        return $posts[0];
    }
}

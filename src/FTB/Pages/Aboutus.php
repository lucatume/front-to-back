<?php


class FTB_Pages_AboutUs extends FTB_Pages_Filters {

	protected $page_name = 'about_us';

	protected $page_slug = 'about-us';

	protected $custom_fields = array(
		'_thumbnail_id'           => 'featured_image',
		'_featured_image_caption' => 'featured_image_caption',
	);
}
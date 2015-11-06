<?php

namespace tad\FrontToBack\Templates;


class TemplateFactory {

	public static function make() {
		return new PageTemplate();
	}
}
<?php

namespace tad\FrontToBack\Exceptions;


class MissingMasterTemplate extends  BaseException{


	/**
		 * @return string
		 */
	protected function intro() {
		return "the master template is missing from the templates folder.";
	}
}
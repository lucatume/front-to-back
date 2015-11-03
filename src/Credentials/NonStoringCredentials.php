<?php

namespace tad\FrontToBack\Credentials;


class NonStoringCredentials implements CredentialsInterface {

	public function get_for_user( $user_id ) {
		return false;
	}

	public function set_for_user( $user_id, $data ) {
		return true;
	}

	public function delete_for_user( $user_id ) {
		return true;
	}
}
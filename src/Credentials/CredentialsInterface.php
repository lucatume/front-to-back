<?php

namespace tad\FrontToBack\Credentials;


interface CredentialsInterface {

	public function get_for_user( $user_id );

	public function set_for_user( $user_id, $data );

	public function delete_for_user( $user_id );
}
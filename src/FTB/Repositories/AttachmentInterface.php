<?php


interface FTB_Repositories_AttachmentInterface {

	public function find_by_url( $url, $refetch = false );
}
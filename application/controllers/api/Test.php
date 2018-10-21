<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Test extends REST_Controller {
	
	public function index_get()
	{
		$data = array(
			'status' => TRUE,
			'message' => 'success'
		);
		$this->response($data, REST_Controller::HTTP_OK);

	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Lists extends REST_Controller {

	private $_rule = array(
		'list_id' => 'required|max_length[10]|numeric',
		'list_name' => 'required|max_length[50]'
	);

	public function __construct()
	{
		parent::__construct();

		// Load model
		$this->load->model(array('lists_m'));

		// Load library
		$this->load->library('response_lib');
	}

	private function _is_duplicate($list_name)
	{
		$duplicate = $this->lists_m->get_info_by_name($list_name);
		if ($duplicate === FALSE)
		{
			return FALSE;
		}
		else
		{
			// Duplicate
			return TRUE;
		}
	}
	
	public function all_get()
	{
		// Get all
		$data = $this->lists_m->all();
		$this->response($this->response_lib->result('success', '', $data), $this->response_lib->status_code);
	}

	public function index_get()
	{
		// Validation
		$this->form_validation->set_data($this->input->get());
		$this->form_validation->set_rules('list_id', 'List Id', $this->_rule['list_id']);
		if ($this->form_validation->run() == FALSE)
		{
			$this->response($this->response_lib->result('error_invalid_parameter', 'list_id'), $this->response_lib->status_code);
		}

		// Get list by list_id
		$list_id = $this->get('list_id');
		$data = $this->lists_m->get_info($list_id);
		if ($data === FALSE)
		{
			$this->response($this->response_lib->result('error_data_not_found'), $this->response_lib->status_code);
		}

		// Success
		$this->response($this->response_lib->result('success', '', $data), $this->response_lib->status_code);
	}

	public function index_post()
	{
		// Validation
		$this->form_validation->set_rules('list_name', 'List Name', $this->_rule['list_name']);
		if ($this->form_validation->run() == FALSE)
		{
			$this->response($this->response_lib->result('error_invalid_parameter', 'list_name'), $this->response_lib->status_code);
		}

		// Request
		$list_name = $this->post('list_name');

		// Check duplicate?
		if ($this->_is_duplicate($list_name))
		{
			$this->response($this->response_lib->result('error_duplicate_data'), $this->response_lib->status_code);
		}

		// Create list
		$result = $this->lists_m->add($list_name);
		if ($result === FALSE)
		{
			$this->response($this->response_lib->result('error_database'), $this->response_lib->status_code);
		}

		// Success
		$data = $this->lists_m->get_info($result);
		$this->response($this->response_lib->result('success_create', '', $data), $this->response_lib->status_code);
	}

	public function index_patch()
	{
		// Validation
		$this->form_validation->set_data($this->patch());
		$this->form_validation->set_rules('list_id', 'List Id', $this->_rule['list_id']);
		$this->form_validation->set_rules('list_name', 'List Name', $this->_rule['list_name']);
		if ($this->form_validation->run() == FALSE)
		{
			$first_key = key($this->form_validation->error_array());
			$this->response($this->response_lib->result('error_invalid_parameter', $first_key), $this->response_lib->status_code);
		}

		// Request
		$list_id = $this->patch('list_id');
		$list_name = $this->patch('list_name');

		// Get list info
		$info = $this->lists_m->get_info($list_id);
		if ($info === FALSE)
		{
			$this->response($this->response_lib->result('error_data_not_found'), $this->response_lib->status_code);
		}

		if ($info['list_name'] != $list_name)
		{
			// Check duplicate?
			if ($this->_is_duplicate($list_name))
			{
				$this->response($this->response_lib->result('error_duplicate_data'), $this->response_lib->status_code);
			}
		}

		// Edit
		$result = $this->lists_m->edit($list_id, $list_name);
		if ($result === FALSE)
		{
			$this->response($this->response_lib->result('error_database'), $this->response_lib->status_code);
		}

		// Success
		$data = $this->lists_m->get_info($result);
		$this->response($this->response_lib->result('success_edit', '', $data), $this->response_lib->status_code);
	}

	public function index_delete()
	{
		// Validation
		$this->form_validation->set_data($this->delete());
		$this->form_validation->set_rules('list_id', 'List Id', $this->_rule['list_id']);
		if ($this->form_validation->run() == FALSE)
		{
			$first_key = key($this->form_validation->error_array());
			$this->response($this->response_lib->result('error_invalid_parameter', $first_key), $this->response_lib->status_code);
		}

		// Request
		$list_id = $this->delete('list_id');
		
		// Get list info
		$info = $this->lists_m->get_info($list_id);
		if ($info === FALSE)
		{
			$this->response($this->response_lib->result('error_data_not_found'), $this->response_lib->status_code);
		}

		// Delete
		$result = $this->lists_m->delete($list_id);
		if ($result === FALSE)
		{
			$this->response($this->response_lib->result('error_database'), $this->response_lib->status_code);
		}

		// Success
		$this->response($this->response_lib->result('success_delete', '', FALSE), $this->response_lib->status_code);

	}

	public function tasks_get()
	{
		// Validation
		$this->form_validation->set_data($this->input->get());
		$this->form_validation->set_rules('list_id', 'List Id', $this->_rule['list_id']);
		if ($this->form_validation->run() == FALSE)
		{
			$this->response($this->response_lib->result('error_invalid_parameter', 'list_id'), $this->response_lib->status_code);
		}

		// Request
		$list_id = $this->get('list_id');

		// Get list info
		$info = $this->lists_m->get_info($list_id);
		if ($info === FALSE)
		{
			$this->response($this->response_lib->result('error_data_not_found'), $this->response_lib->status_code);
		}

		// List task by list id
		$data = $this->lists_m->list_tasks($list_id);
		$this->response($this->response_lib->result('success', '', $data), $this->response_lib->status_code);

	}
}

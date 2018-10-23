<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Tasks extends REST_Controller {

	private $_rule = array(
		'list_id' => 'required|max_length[10]|numeric',
		'task_id' => 'required|max_length[10]|numeric',
		'task_name' => 'required|max_length[50]',
		'task_name_edit' => 'max_length[50]',
		'task_detail' => 'max_length[100]',
		'due_date' => 'max_length[20]|valid_datetime',
		'status' => 'numeric|in_list[1,2]'
	);

	public function __construct()
	{
		parent::__construct();

		// Load model
		$this->load->model(array('tasks_m', 'lists_m'));

		// Load library
		$this->load->library('response_lib');
	}

	public function index_get()
	{
		// Validation
		$this->form_validation->set_data($this->input->get());
		$this->form_validation->set_rules('task_id', 'List Id', $this->_rule['task_id']);
		if ($this->form_validation->run() == FALSE)
		{
			$this->response($this->response_lib->result('error_invalid_parameter', 'task_id'), $this->response_lib->status_code);
		}

		// Get task
		$task_id = $this->get('task_id');
		$data = $this->tasks_m->get_info($task_id);
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
		$this->form_validation->set_rules('list_id', 'List Id', $this->_rule['list_id']);
		$this->form_validation->set_rules('task_name', 'Task Name', $this->_rule['task_name']);
		$this->form_validation->set_rules('task_detail', 'Task Detail', $this->_rule['task_detail']);
		$this->form_validation->set_rules('due_date', 'Due Date', $this->_rule['due_date']);
		if ($this->form_validation->run() == FALSE)
		{
			$first_key = key($this->form_validation->error_array());
			$this->response($this->response_lib->result('error_invalid_parameter', $first_key), $this->response_lib->status_code);
		}

		// Request
		$list_id = $this->post('list_id');
		$task_name = $this->post('task_name');
		$task_detail = $this->post('task_detail');
		$due_date = $this->post('due_date');

		// Get list info
		$info = $this->lists_m->get_info($list_id);
		if ($info === FALSE)
		{
			$this->response($this->response_lib->result('error_data_not_found'), $this->response_lib->status_code);
		}

		// Create task
		$result = $this->tasks_m->add($list_id, $task_name, $task_detail, $due_date);
		if ($result === FALSE)
		{
			$this->response($this->response_lib->result('error_database'), $this->response_lib->status_code);
		}

		// Success
		$data = $this->tasks_m->get_info($result);
		$this->response($this->response_lib->result('success_create', '', $data), $this->response_lib->status_code);

	}

	public function index_patch()
	{
		// Validation
		$this->form_validation->set_data($this->patch());
		$this->form_validation->set_rules('task_id', 'Task Id', $this->_rule['task_id']);
		$this->form_validation->set_rules('task_name', 'Task Name', $this->_rule['task_name_edit']);
		$this->form_validation->set_rules('task_detail', 'Task Detail', $this->_rule['task_detail']);
		$this->form_validation->set_rules('due_date', 'Due Date', $this->_rule['due_date']);
		if ($this->form_validation->run() == FALSE)
		{
			$first_key = key($this->form_validation->error_array());
			$this->response($this->response_lib->result('error_invalid_parameter', $first_key), $this->response_lib->status_code);
		}

		// Request
		$task_id = $this->patch('task_id');
		$task_name = $this->patch('task_name');
		$task_detail = $this->patch('task_detail');
		$due_date = $this->patch('due_date');

		if ( ! is_null($task_name) && (strlen($task_name) == 0))
		{
			$this->response($this->response_lib->result('error_invalid_parameter', 'task_name'), $this->response_lib->status_code);
		}
		
		// Get task info
		$info = $this->tasks_m->get_info($task_id);
		if ($info === FALSE)
		{
			$this->response($this->response_lib->result('error_data_not_found'), $this->response_lib->status_code);
		}

		// Edit task
		$result = $this->tasks_m->edit($task_id, $task_name, $task_detail, $due_date);
		if ($result === FALSE)
		{
			$this->response($this->response_lib->result('error_database'), $this->response_lib->status_code);
		}

		// Success
		$data = $this->tasks_m->get_info($result);
		$this->response($this->response_lib->result('success_edit', '', $data), $this->response_lib->status_code);

	}

	public function status_patch()
	{
		// Validation
		$this->form_validation->set_data($this->patch());
		$this->form_validation->set_rules('task_id', 'Task Id', $this->_rule['task_id']);
		$this->form_validation->set_rules('status', 'Status', 'required|'.$this->_rule['status']);
		if ($this->form_validation->run() == FALSE)
		{
			$first_key = key($this->form_validation->error_array());
			$this->response($this->response_lib->result('error_invalid_parameter', $first_key), $this->response_lib->status_code);
		}

		// Request
		$task_id = $this->patch('task_id');
		$status = $this->patch('status');
		
		// Get task info
		$info = $this->tasks_m->get_info($task_id);
		if ($info === FALSE)
		{
			$this->response($this->response_lib->result('error_data_not_found'), $this->response_lib->status_code);
		}

		// Edit status
		$result = $this->tasks_m->edit_status($task_id, $status);
		if ($result === FALSE)
		{
			$this->response($this->response_lib->result('error_database'), $this->response_lib->status_code);
		}

		// Success
		$data = $this->tasks_m->get_info($result);
		$this->response($this->response_lib->result('success_edit', '', $data), $this->response_lib->status_code);

	}

	public function index_delete()
	{
		// Validation
		$this->form_validation->set_data($this->delete());
		$this->form_validation->set_rules('task_id', 'Task Id', $this->_rule['task_id']);
		if ($this->form_validation->run() == FALSE)
		{
			$first_key = key($this->form_validation->error_array());
			$this->response($this->response_lib->result('error_invalid_parameter', $first_key), $this->response_lib->status_code);
		}

		// Request
		$task_id = $this->delete('task_id');
		
		// Get task info
		$info = $this->tasks_m->get_info($task_id);
		if ($info === FALSE)
		{
			$this->response($this->response_lib->result('error_data_not_found'), $this->response_lib->status_code);
		}

		// Delete
		$result = $this->tasks_m->delete($task_id);
		if ($result === FALSE)
		{
			$this->response($this->response_lib->result('error_database'), $this->response_lib->status_code);
		}

		// Success
		$this->response($this->response_lib->result('success_delete', '', FALSE), $this->response_lib->status_code);

	}
}

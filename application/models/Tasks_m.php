<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks_m extends CI_Model {

	public $table = 'tasks';
	public $view = 'v_tasks';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
     * Get task info
	 * 
     * @param  integer  $task_id
     * @return mixed
     */
	public function get_info($task_id)
	{
		$query = $this->db->get_where($this->view, array('task_id' => $task_id));
		$result = $query->row_array();
		if (empty($result))
		{
			return FALSE;
		}

		return $result;
	}

	/**
     * Add task
	 * 
     * @param  integer  $list_id
     * @param  string   $task_name
     * @param  string   $task_detail
     * @param  date     $due_date
     * @return mixed
     */
	public function add($list_id, $task_name, $task_detail = '', $due_date = '')
	{
		$task_detail = (is_null($task_detail)) ? '' : $task_detail;
		$due_date = (is_null($due_date)) ? '' : $due_date;

		$data = array(
			'list_id' => $list_id,
			'task_name' => $task_name,
			'task_detail' => $task_detail,
			'due_date' => $due_date
		);
		
		$this->db->insert($this->table, $data);
		if ($this->db->affected_rows() == 0)
		{
			return FALSE;
		}

		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	/**
     * Add task
	 * 
     * @param  integer  $list_id
     * @param  string   $task_name
     * @param  string   $task_detail
     * @param  date     $due_date
     * @return mixed
     */
	public function edit($task_id, $task_name = null, $task_detail = null, $due_date = null)
	{

		$data = array(
			'updated_date' => date('Y-m-d H:i:s')
		);

		if ( ! is_null($task_name))
		{
			$data['task_name'] = $task_name;
		}

		if ( ! is_null($task_detail))
		{
			$data['task_detail'] = $task_detail;
		}

		if ( ! is_null($due_date))
		{
			$data['due_date'] = $due_date;
		}

		$this->db->where('task_id', $task_id);
		$this->db->update($this->table, $data);

		if ($this->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return $task_id;
	}

	/**
     * Edit task status
	 * 
     * @param  integer  $task_id
     * @param  integer  $status
     * @return mixed
     */
	public function edit_status($task_id, $status)
	{
		$data = array(
			'status' => $status,
			'updated_date' => date('Y-m-d H:i:s')
		);

		$this->db->where('task_id', $task_id);
		$this->db->update($this->table, $data);

		if ($this->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return $task_id;
	}

	/**
     * Delete task
	 * 
     * @param  integer  $task_id
     * @return boolean
     */
	public function delete($task_id)
	{
		$this->db->where('task_id', $task_id);
		$this->db->delete($this->table);

		if ($this->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return TRUE;
	}


}
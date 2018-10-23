<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lists_m extends CI_Model {

	protected $table = 'lists';
	protected $v_tasks = 'v_tasks';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
     * List all
	 * 
     * @return mixed
     */
	public function all()
	{
		$query = $this->db->get($this->table);
		$result = $query->result_array();
		return empty($result) ? FALSE : $result;
	}

	/**
     * Get list info
	 * 
     * @param  integer  $list_id
     * @return mixed
     */
	public function get_info($list_id)
	{
		$query = $this->db->get_where($this->table, array('list_id' => $list_id));
		$result = $query->row_array();
		if (empty($result))
		{
			return FALSE;
		}

		return $result;
	}

	/**
     * Get list info by list_name
	 * 
     * @param  string  $list_name
     * @return mixed
     */
	public function get_info_by_name($list_name)
	{
		$query = $this->db->get_where($this->table, array('list_name' => $list_name));
		$result = $query->row_array();
		if (empty($result))
		{
			return FALSE;
		}

		return $result;
	}

	/**
     * Add list
	 * 
     * @param  string  $list_name
     * @return mixed
     */
	public function add($list_name)
	{
		$data = array(
			'list_name' => $list_name
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
     * Edit list
	 * 
     * @param  integer  $list_id
     * @param  string   $list_name
     * @return mixed
     */
	public function edit($list_id, $list_name)
	{
		$data = array(
			'list_name' => $list_name,
			'updated_date' => date('Y-m-d H:i:s')
		);

		$this->db->where('list_id', $list_id);
		$this->db->update($this->table, $data);

		if ($this->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return $list_id;
	}

	/**
     * Delete list
	 * 
     * @param  integer  $list_id
     * @return boolean
     */
	public function delete($list_id)
	{
		$this->db->where('list_id', $list_id);
		$this->db->delete($this->table);

		if ($this->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
     * List tasks by list_id
	 * 
     * @param  integer  $list_id
     * @return mixed
     */
	public function list_tasks($list_id)
	{
		$query = $this->db->get_where($this->v_tasks, array('list_id' => $list_id));
		$result = $query->result_array();
		return $result;
	}

}
<?php

/**
 * Class Pages
 */
class Pages extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Add new row into the table
     *
     * @param $data
     * @return bool
     */
    function add($data) {
        $query = $this->db->insert('droppy_pages', $data);

        if($query) {
            return true;
        }
        return false;
    }

    /**
     * Will return all rows
     * @param int $start
     * @param int $limit
     * @return array|bool
     */
    function getAll($start = 0, $limit = 0, $lang = '') {
        $this->db->select('*');
        $this->db->from('droppy_pages');
        if($lang != '') {
            $this->db->where('lang', $lang);
        }
        if($limit > 0) {
            $this->db->limit($limit, $start);
        }
        $this->db->order_by('order', 'ASC');

        $query = $this->db->get();

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    /**
     * Will return total amount of rows
     *
     * @return int
     */
    function getTotal() {
        $this->db->select('id');
        $this->db->from('droppy_pages');

        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * Select an row by ID
     *
     * @param $id
     * @return array|bool
     */
    function getByID($id) {
        $this->db->select('*');
        $this->db->from('droppy_pages');
        $this->db->where('id', $id);
        $this->db->limit(1);

        $query = $this->db->get();

        if($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    /**
     * Get rows by type
     *
     * @param $type
     * @return array|false|null
     */
    function getByType($type) {
        $this->db->select('*');
        $this->db->from('droppy_pages');
        $this->db->where('type', $type);

        $query = $this->db->get();

        if($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    /**
     * Update row by ID
     *
     * @param $data
     * @param $id
     * @return bool
     */
    function updateByID($data, $id) {
        $this->db->where('id', $id);
        if($this->db->update('droppy_pages', $data)) {
            return true;
        }
        return false;
    }

    /**
     * Delete a row by ID
     *
     * @param $id
     * @return bool
     */
    function deleteByID($id) {
        $this->db->delete('droppy_pages', array('id' => $id));

        return true;
    }
}
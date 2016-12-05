<?php

class Package_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }
    
    /*
     * This method will return all operators
     * @author nazmul hasan on 18th november 2016
     */
    public function get_all_operators()
    {
        return $this->db->select($this->tables['operators'] . '.id as operator_id,' . $this->tables['operators'] . '.*')
                    ->from($this->tables['operators'])
                    ->get();
    }

    /*
     * This method will return all packages
     * @param $operator_id, operator id
     * @author nazmul hasan on 18th november 2016
     */
    public function get_all_packages($operator_id = 0)
    {
        if($operator_id != 0)
        {
            $this->db->where($this->tables['operators'].'.id', $operator_id);
        }
        $this->db->order_by($this->tables['packages'].'.operator_id', 'asc');
        return $this->db->select($this->tables['packages'] . '.id as package_id,' . $this->tables['packages'] . '.title as package_title,' . $this->tables['packages'] . '.*,' . $this->tables['operators'] . '.title as operator_title')
                    ->from($this->tables['packages'])
                    ->join($this->tables['operators'], $this->tables['operators'] . '.id=' . $this->tables['packages'] . '.operator_id')
                    ->get();
    }
    
    /*
     * This method will return package info
     * @param $package_id, package id
     * @author nazmul hasan on 18th november 2016
     */
    public function get_package_info($package_id)
    {
        $this->db->where($this->tables['packages'].'.id', $package_id);
        return $this->db->select($this->tables['packages'] . '.id as package_id,' . $this->tables['packages'] . '.*')
                    ->from($this->tables['packages'])
                    ->get();
    }
    
    /*
     * This method will create a new package
     * @param $package_data, package data
     * @author nazmul hasan on 18th november 2016
     */
    public function create_package($package_data)
    {
        $current_time = now();
        $package_data['created_on'] = $current_time;
        $package_data['modified_on'] = $current_time;
        $data = $this->_filter_data($this->tables['packages'], $package_data);
        $this->db->insert($this->tables['packages'], $data);
        $package_id = $this->db->insert_id();
        if(isset($package_id))
        {
            $this->set_message('create_package_successful');
            return TRUE;
        }
        else
        {
            $this->set_error('create_package_unsuccessful');
            return FALSE;
        }
    }
    
    /*
     * This method will update a package info
     * @param $package_id, package id
     * @param $package_data, package data to be updated
     * @author nazmul hasan on 18th november 2016
     */
    public function update_package($package_id, $package_data)
    {
        $current_time = now();
        $package_data['modified_on'] = $current_time;
        // Filter the data passed
        $data = $this->_filter_data($this->tables['packages'], $package_data);
        $this->db->update($this->tables['packages'], $data, array('id' => $package_id));
        if ($this->db->trans_status() === FALSE) 
        {
            $this->set_error('update_package_unsuccessful');
            return FALSE;
        }
        else
        {
            $this->set_message('update_package_successful');
            return TRUE;
        }
    }
    
    /*
     * This method will delete an existing package
     * @param $package_id, package id
     * @author nazmul hasan on 18th november 2016
     */
    public function delete_package($package_id)
    {
        $this->db->delete($this->tables['packages'], array('id' => $package_id));
        if ($this->db->affected_rows() == 0) {
            $this->set_error('delete_package_unsuccessful');
            return FALSE;
        }
        else
        {
            $this->set_message('delete_package_successful');
            return TRUE;
        }
    }
}
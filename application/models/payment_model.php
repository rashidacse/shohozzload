<?php

class Payment_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return current balance of users
     * @param $user_id_list, user id list
     * @author nazmul hasan on 24th february 2016
     */
    public function get_users_current_balance($user_id_list = array()) {
        $this->db->where_in('status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_PROCESSED, TRANSACTION_STATUS_ID_SUCCESSFUL));
        $this->db->where_in('user_id', $user_id_list);
        $this->db->group_by('user_id');
        return $this->db->select('user_id, sum(balance_in) - sum(balance_out) as current_balance')
                        ->from($this->tables['user_payments'])
                        ->get();
    }
    
    /*
     * This method will return payment history
     * @param $type_id_list, payment types
     * @param $status_id_list, status id list
     * @param $start_time start time in unix
     * @param $end_time end time in unix
     * @param $limit limit
     * @param $offset offset
     * @param $order order
     * @author nazmul hasan on 24th february 2016
     */
    public function get_payment_history($type_id_list = array(), $status_id_list = array(), $start_time = 0, $end_time = 0, $limit = 0, $offset = 0, $order = 'desc', $user_id_list = array(), $reference_id_list = array()) {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if ($offset > 0) {
            $this->db->offset($offset);
        }
        if ($start_time != 0 && $end_time != 0) {
            $this->db->where($this->tables['user_payments'] . '.created_on >=', $start_time);
            $this->db->where($this->tables['user_payments'] . '.created_on <=', $end_time);
        }
        if (!empty($user_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.user_id', $user_id_list);
        }
        if (!empty($reference_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.reference_id', $reference_id_list);
        }
        if (!empty($type_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.type_id', $type_id_list);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.status_id', $status_id_list);
        }
        $this->db->order_by($this->tables['user_payments'] . '.id', $order);
        return $this->db->select($this->tables['user_payments'] . '.*,(' . $this->tables['user_payments'] . '.balance_in+' . $this->tables['user_payments'] . '.balance_out) as amount,' . $this->tables['user_payment_types'] . '.title, users_source.username as source_username, users_destination.username as destination_username')
                        ->from($this->tables['user_payments'])
                        ->join($this->tables['user_payment_types'], $this->tables['user_payment_types'] . '.id=' . $this->tables['user_payments'] . '.type_id')
                        ->join($this->tables['users'].' AS users_source', 'users_source.id='.$this->tables['user_payments'].'.reference_id')
                        ->join($this->tables['users'].' AS users_destination', 'users_destination.id='.$this->tables['user_payments'].'.user_id')
                        ->get();
    }
    
        /*
     * This method will return payment history summery
     * @param $type_id_list, payment types
     * @param $status_id_list, status id list
     * @param $start_time start time in unix
     * @param $end_time end time in unix
     * @author rashida on 26th April 2016
     */
    
    function get_payment_history_summary($type_id_list = array(), $status_id_list = array(), $start_time = 0, $end_time = 0, $user_id_list = array(), $reference_id_list = array()){
        //run each where that was passed
         if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        if ($start_time != 0 && $end_time != 0) {
            $this->db->where($this->tables['user_payments'] . '.created_on >=', $start_time);
            $this->db->where($this->tables['user_payments'] . '.created_on <=', $end_time);
        }
        if (!empty($user_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.user_id', $user_id_list);
        }
        if (!empty($reference_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.reference_id', $reference_id_list);
        }
        if (!empty($type_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.type_id', $type_id_list);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.status_id', $status_id_list);
        }
        return $this->db->select('COUNT(*) as total_transactions, sum(balance_out) as total_amount_out, sum(balance_in) as total_amount_in')
                        ->from($this->tables['user_payments'])
                        ->get();
   }
    
    public function get_receive_history($type_id_list = array(), $limit = 0) {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }

            $this->_ion_where = array();
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if (!empty($type_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.type_id', $type_id_list);
        }
        $this->db->order_by($this->tables['user_payments'] . '.id', 'desc');
        return $this->db->select($this->tables['user_payments'] . '.*,' . $this->tables['user_payment_types'] . '.title')
                        ->from($this->tables['user_payments'])
                        ->join($this->tables['user_payment_types'], $this->tables['user_payment_types'] . '.id=' . $this->tables['user_payments'] . '.type_id')
                        ->get();
    }
    /*
     * This method will transfer payment from one user to another user
     * @param $payment_data, payment information
     * @author nazmul hasan on 24th february 2016
     */
    public function transfer_user_payment($payment_data) {
        $current_time = now();        
        $payment_data['created_on'] = $current_time;
        $payment_data['modified_on'] = $current_time;
        $payment_data['status_id'] = TRANSACTION_STATUS_ID_SUCCESSFUL;
        $data = $this->_filter_data($this->tables['user_payments'], $payment_data);
        $this->db->insert($this->tables['user_payments'], $data);
        $id = $this->db->insert_id();
        if (isset($id)) {
            return TRUE;
        }
        return FALSE;
    }
    
    
    //This method may not be used in future....
    public function get_user_current_balance($user_id) {
        $this->db->where_in($this->tables['user_payments'] . '.status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_PROCESSED, TRANSACTION_STATUS_ID_SUCCESSFUL));
        $this->db->where($this->tables['user_payments'] . '.user_id', $user_id);
        return $this->db->select('user_id, sum(balance_in) - sum(balance_out) as current_balance')
                        ->from($this->tables['user_payments'])
                        ->get();
    }
    
    /*
     * this method will return users used services info
     * @param $user_id_list, user id list
     * @author nazmul hasan on 18th october 2016
     */
    public function get_users_used_services($user_id_list = array())
    {
        if(!empty($user_id_list))
        {
            $this->db->where_in($this->tables['user_payments'] . '.user_id',$user_id_list);
        }
        $this->db->where_in($this->tables['user_payments'] . '.status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_PROCESSED, TRANSACTION_STATUS_ID_SUCCESSFUL));
        $this->db->where($this->tables['user_payments'] . '.type_id', PAYMENT_TYPE_ID_USE_SERVICE);
        $this->db->group_by($this->tables['user_payments'] . '.user_id');
        return $this->db->select('user_id, sum(balance_out) as amount')
                        ->from($this->tables['user_payments'])
                        ->get();
    }
    /*
     * this method will return users payment info
     * @param $user_id_list, user id list
     * @author nazmul hasan on 18th october 2016
     */
    public function get_users_payments($user_id_list = array())
    {
        if(!empty($user_id_list))
        {
            $this->db->where_in($this->tables['user_payments'] . '.user_id',$user_id_list);
        }
        $this->db->where_in($this->tables['user_payments'] . '.type_id', array(PAYMENT_TYPE_ID_SEND_CREDIT, PAYMENT_TYPE_ID_RETURN_CREDIT, PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT, PAYMENT_TYPE_ID_LOAD_BALANCE, PAYMENT_TYPE_ID_COMMISSION));
        $this->db->where_in($this->tables['user_payments'] . '.status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_PROCESSED, TRANSACTION_STATUS_ID_SUCCESSFUL));
        $this->db->group_by($this->tables['user_payments'] . '.user_id');
        return $this->db->select('user_id, sum(balance_in - balance_out) as amount')
                        ->from($this->tables['user_payments'])
                        ->get();
    }
}

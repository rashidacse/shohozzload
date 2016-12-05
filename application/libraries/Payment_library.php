<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Payment_library {

    public function __construct() {
        $this->load->model('payment_model');
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->payment_model, $method)) {
            throw new Exception('Undefined method Payment_library::' . $method . '() called');
        }

        return call_user_func_array(array($this->payment_model, $method), $arguments);
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access	public
     * @param	$var
     * @return	mixed
     */
    public function __get($var) {
        return get_instance()->$var;
    }

    /*
     * This method will return payment history
     * @author nazmul hasan on 3rd March 2016
     */
    public function get_payment_history($type_id_list = array(), $status_id_list = array(), $start_date = 0, $end_date = 0, $limit = 0, $offset = 0, $order = 'desc', $where = array(), $user_id_list = array(), $reference_id_list = array()) {
        $this->load->library('Date_utils');
        $payment_list = array();
        $payment_information = array();
        $start_time = 0;
        $end_time = 0;
        if ($start_date != 0 && $end_date != 0) {
            $start_time = $this->date_utils->server_start_unix_time_of_date($start_date);
            $end_time = $this->date_utils->server_end_unix_time_of_date($end_date);
        }
        $total_transactions = 0;
        $total_amount_out = 0;
        $total_amount_in = 0;
        if (!empty($where)) {
            $this->payment_model->where($where);
        }
        $payment_summery_array = $this->payment_model->get_payment_history_summary($type_id_list, $status_id_list, $start_time, $end_time, $user_id_list, $reference_id_list)->result_array();
        if (!empty($payment_summery_array)) {
            $total_transactions = (int) $payment_summery_array[0]['total_transactions'];
            $total_amount_out = (int) $payment_summery_array[0]['total_amount_out'];
            $total_amount_in = (int) $payment_summery_array[0]['total_amount_in'];
        }
        if (!empty($where)) {
            $this->payment_model->where($where);
        }
        $payment_list_array = $this->payment_model->get_payment_history($type_id_list, $status_id_list, $start_time, $end_time, $limit, $offset, $order, $user_id_list, $reference_id_list)->result_array();
        foreach ($payment_list_array as $payment_info) {
            $payment_info['created_on'] = $this->date_utils->get_unix_to_display($payment_info['created_on']);
            $payment_list[] = $payment_info;
        }
        $payment_information['total_transactions'] = $total_transactions;
        $payment_information['total_amount_out'] = $total_amount_out;
        $payment_information['total_amount_in'] = $total_amount_in;
        $payment_information['payment_list'] = $payment_list;
        return $payment_information;
    }
    
    /*
     * this method will return current balance of users
     * @param $user_id_list, user id list
     * @author nazmul hasan on 18th october 2016
     */
    public function get_users_current_balances($user_id_list)
    {
        $response = array();
        $user_id_successor_list_map = array();
        $user_id_used_services_map = array();
        $user_id_payments_map = array();
        $user_ids = array();
        $this->load->library('reseller_library');
        foreach($user_id_list as $user_id)
        {
            //return successor list including this user
            $successor_id_list = $this->reseller_library->get_successor_id_list($user_id, TRUE);
            if(!in_array($user_id, $user_id_successor_list_map))
            {
                $user_id_successor_list_map[$user_id] = $successor_id_list;
                if(!in_array($user_id, $user_ids))
                {
                    $user_ids[] = $user_id;
                }
                foreach($successor_id_list as $successor_user_id)
                {
                    if(!in_array($successor_user_id, $user_ids))
                    {
                        $user_ids[] = $successor_user_id;
                    }
                }
            }
        }
        foreach($user_ids as $temp_user_id)
        {
            $user_id_used_services_map[$temp_user_id] = 0;
            $user_id_payments_map[$temp_user_id] = 0;
        }
        $users_used_services_array = $this->payment_model->get_users_used_services($user_ids)->result_array();
        foreach($users_used_services_array as $used_service_info)
        {
            $user_id_used_services_map[$used_service_info['user_id']] = $used_service_info['amount'];
        }
        $users_payments_array = $this->payment_model->get_users_payments($user_ids)->result_array();
        foreach($users_payments_array as $payment_info)
        {
            $user_id_payments_map[$payment_info['user_id']] = $payment_info['amount'];
        }
        //generating current balance of each user
        foreach($user_id_list as $user_id)
        {
            $total_payment = (float)0.0;
            $total_used_service = (float)0.0;
            $successor_id_list = array();
            if(array_key_exists($user_id, $user_id_successor_list_map))
            {
                $successor_id_list = $user_id_successor_list_map[$user_id];
            } 
            else
            {
                $successor_id_list[] = $user_id;
            }
            foreach($successor_id_list as $s_user_id)
            {
                if(array_key_exists($s_user_id, $user_id_used_services_map))
                {
                    $total_used_service = (float)$total_used_service + (float)$user_id_used_services_map[$s_user_id];
                }
            }
            if(array_key_exists($user_id, $user_id_payments_map))
            {
                $total_payment = (float)$total_payment + (float)$user_id_payments_map[$user_id];
            }
            $response[] = array(
                'user_id' => $user_id,
                'current_balance' => (float)($total_payment-$total_used_service),
                'total_payment' => $total_payment,
                'total_used_service' => $total_used_service
            );
        }
        return $response;
    }
    
    /*
     * this method will return current balance of a user
     * @param $user_id, user id
     * @author nazmul hasan on 18th october 2016
     */
    public function get_user_current_balance($user_id)
    {
        if (0 == $user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $current_balance = 0;
        $result = $this->get_users_current_balances(array($user_id));
        if(!empty($result))
        {
            $current_balance = $result[0]['current_balance'];
        }
        return $current_balance;
    }
    /*
     * this method will return whether user has permission to execute a transaction or not
     * @param $user_id, user id
     * @param $amount, amount
     * @return boolean
     * @author nazmul hasan on 18th october 2016
     */
    public function is_transaction_amount_permitted($user_id, $amount, $return_type_id = 0)
    {
        $response = array(
            'balance' => 0,
            'message' => "",
            'status' => TRUE
        );
        $this->load->library('reseller_library');
        $predecessor_id_list = $this->reseller_library->get_predecessor_id_list($user_id, TRUE);
        if(!empty($predecessor_id_list))
        {
            $users_current_balances = $this->get_users_current_balances($predecessor_id_list);
            foreach($users_current_balances as $user_balance_info)
            {
                if($return_type_id == TRANSACTION_BALANCE_CHECK_USER_BALANCE && $user_id == $user_balance_info['user_id'])
                {
                    $response['balance'] = $user_balance_info['current_balance'];
                }
                if($amount > $user_balance_info['current_balance'])
                {
                    $response['status'] = FALSE;
                    //return FALSE;
                }
            }
        }
        return $response;
        //return TRUE;
    }
}

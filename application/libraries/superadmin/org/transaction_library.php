<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Transaction_library {

    public function __construct() {
        $this->load->model("superadmin/org/transaction_model");
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->transaction_model, $method)) {
            throw new Exception('Undefined method Transaction_library::' . $method . '() called');
        }

        return call_user_func_array(array($this->transaction_model, $method), $arguments);
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
     * This method will return transaction list based on offset and limit, total number of transactions without offset and limit 
     * @param $user_id_list, user id list
     * @param $service_id_list, service id list
     * @param $process_id_list, process id list
     * @param $cell_no, transaction cell number
     * @param $from_date, start date of transaction
     * @param $to_date, end date of transaction
     * @param $offset, offset
     * @param $limit, limit
     * @author nazmul hasan on 9th october 2016
     */
    public function get_transaction_list($user_id_list = array(), $service_id_list, $status_id_list, $process_id_list, $cell_no = '', $from_date = 0, $to_date = 0, $offset = 0, $limit = 0) {
        $this->load->library('superadmin/org/super_utils');
        $from_unix_time = 0;
        if ($from_date != 0) {
            $from_unix_time = $this->super_utils->server_start_unix_time_of_date($from_date);
        }
        $to_unix_time = 0;
        if ($to_date != 0) {
            $to_unix_time = $this->super_utils->server_end_unix_time_of_date($to_date);
        }
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_summary_array = $this->transaction_model->get_transaction_summary($user_id_list, $service_id_list, $status_id_list, $process_id_list, $cell_no, $from_unix_time, $to_unix_time)->result_array();
        if (!empty($transaction_summary_array)) {
            $total_transactions = (int) $transaction_summary_array[0]['total_transactions'];
            $total_amount = (double) $transaction_summary_array[0]['total_amount'];
        }
        $transaction_list = $this->transaction_model->get_transaction_list($user_id_list, $service_id_list, $status_id_list, $process_id_list, $cell_no, $from_unix_time, $to_unix_time, $offset, $limit)->result_array();
        $transaction_info_list = array();
        if (!empty($transaction_list)) {
            foreach ($transaction_list as $transaction_info) {
                $transaction_info['created_on'] = $this->super_utils->get_unix_to_human_date($transaction_info['created_on']);
                $transaction_info_list[] = $transaction_info;
            }
        }
        $transaction_information = array();
        //total number of transaction without limit and offset. this value will be used for pagination logic
        $transaction_information['total_transactions'] = $total_transactions;
        $transaction_information['total_amount'] = $total_amount;
        $transaction_information['transaction_list'] = $transaction_info_list;
        return $transaction_information;
    }

    /*
     * this method will return user transaction statuses
     * @author nazmul hasan on 22nd october 2016
     */
    public function get_user_transaction_statuses() {
        $transction_status_list = array();
        $select_all = array(
            "id" => S_SELECT_ALL_STATUSES_TRANSACTIONS,
            "title" => "All",
            "selected" => true
        );
        $transction_status_list[] = $select_all;
        $transction_status_list_array = $this->transaction_model->get_user_transaction_statuses()->result_array();
        if (!empty($transction_status_list_array)) {
            foreach ($transction_status_list_array as $transction_status) {
                $transction_status['selected'] = false;
                $transction_status_list[] = $transction_status;
            }
        }
        return $transction_status_list;
    }

    public function get_transactions_process_types() {
        $transaction_process_type_list = array();
        $type0 = array(
            "id" => S_SELECT_ALL_PROCESSES_TRANSACTIONS,
            "title" => "All"
        );
        $type1 = array(
            "id" => S_TRANSACTION_PROCESS_TYPE_ID_AUTO,
            "title" => "Auto"
        );
        $type2 = array(
            "id" => S_TRANSACTION_PROCESS_TYPE_ID_MANUAL,
            "title" => "Manual"
        );
        $transaction_process_type_list[] = $type0;
        $transaction_process_type_list[] = $type1;
        $transaction_process_type_list[] = $type2;
        return $transaction_process_type_list;
    }

}

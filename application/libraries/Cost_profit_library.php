<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Cost_profit_library {

    public function __construct() {
        $this->load->model('cost_profit_model');
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->cost_profit_model, $method)) {
            throw new Exception('Undefined method Cost_profit_library::' . $method . '() called');
        }

        return call_user_func_array(array($this->cost_profit_model, $method), $arguments);
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
     * This method will return profit history
     * @author nazmul hasan on 3rd March 2016
     */

    public function get_profit_history($status_id_list = array(), $service_id_list = array(), $start_date = 0, $end_date = 0, $limit = 0, $offset = 0, $where = array()) {
        $profit_information = array();
        $this->load->library('Date_utils');
        $start_time = 0;
        $end_time = 0;
        if ($start_date != 0 && $end_date != 0) {
            $start_time = $this->date_utils->server_start_unix_time_of_date($start_date);
            $end_time = $this->date_utils->server_end_unix_time_of_date($end_date);
        }
        $total_transactions = 0;
        $total_amount_in = 0;
        if (!empty($where)) {
            $this->cost_profit_model->where($where);
        }
        $payment_summary_array = $this->cost_profit_model->get_profit_history_summary($status_id_list, $service_id_list, $start_time, $end_time)->result_array();
        if (!empty($payment_summary_array)) {
            $total_transactions = (int) $payment_summary_array[0]['total_transactions'];
        }
        $profit_list = array();
        if (!empty($where)) {
            $this->cost_profit_model->where($where);
        }
        $profit_list_array = $this->cost_profit_model->get_profit_history($status_id_list, $service_id_list, $start_time, $end_time, $limit, $offset)->result_array();
        foreach ($profit_list_array as $profit_info) {
            $profit_info['created_on'] = $this->date_utils->get_unix_to_display($profit_info['created_on']);
            $profit_list[] = $profit_info;
        }
        $profit_information['total_transactions'] = $total_transactions;
        $profit_information['profit_list'] = $profit_list;
        return $profit_information;
    }

    /*
     * this method will return detail report
     * @param $start_date, start date
     * @param $end_date, end date
     * @param $user_id_list, user id list
     * @author nazmul hasan on 19th october 2016
     */
    public function get_report_detail_history($start_date = '', $end_date = '', $user_id_list = array()) {
        $report_list = array();
        $report_summary = array();
        $report_summary['total_request'] = 0;
        $report_summary['total_pending'] = 0;
        $report_summary['total_processed'] = 0;
        $report_summary['total_success'] = 0;        
        $report_summary['total_failed'] = 0;
        $report_summary['total_cancelled'] = 0;
        $report_summary['total_ratio_success'] = 0;
        
        $service_details_map = array();
        $start_time = 0;
        $end_time = 0;
        if ($start_date != '' && $end_date != '') {
            $this->load->library('Date_utils');
            $start_time = $this->date_utils->server_start_unix_time_of_date($start_date);
            $end_time = $this->date_utils->server_end_unix_time_of_date($end_date);
        }
        
        $this->load->model('service_model');
        $service_list_array = $this->service_model->get_service_info_list()->result_array();
        foreach($service_list_array as $service_info)
        {
            $service_details_map[$service_info['service_id']] = array(
                'service_id' => $service_info['service_id'],
                'title' => $service_info['title'],
                'total' => 0,
                'pending' => 0,
                'processed' => 0,
                'success' => 0,
                'failed' => 0,
                'cancelled' => 0,
                'ratio_success' => 0
            );
        }
        $report_list_array = $this->cost_profit_model->get_report_detail_history($start_time, $end_time, $user_id_list)->result_array();
        foreach ($report_list_array as $report_info) 
        {
            if ($report_info['status_id'] == TRANSACTION_STATUS_ID_PENDING) 
            {
                $service_details_map[$report_info['service_id']]['pending'] = $report_info['total_statuses'];
            }
            else if ($report_info['status_id'] == TRANSACTION_STATUS_ID_PROCESSED) 
            {
                $service_details_map[$report_info['service_id']]['processed'] = $report_info['total_statuses'];
            } 
            else if ($report_info['status_id'] == TRANSACTION_STATUS_ID_SUCCESSFUL) 
            {
                $service_details_map[$report_info['service_id']]['success'] = $report_info['total_statuses'];
            } 
            else if ($report_info['status_id'] == TRANSACTION_STATUS_ID_FAILED) 
            {
                $service_details_map[$report_info['service_id']]['failed'] = $report_info['total_statuses'];
            }
            else if ($report_info['status_id'] == TRANSACTION_STATUS_ID_CANCELLED) 
            {
                $service_details_map[$report_info['service_id']]['cancelled'] = $report_info['total_statuses'];
            }
        }
        foreach($service_details_map as $details_info)
        {
            $details_info['total'] = ($details_info['pending'] + $details_info['processed'] + $details_info['success'] + $details_info['failed'] + $details_info['cancelled']);
            if($details_info['total'] > 0)
            {
                $details_info['ratio_success'] = round((float)($details_info['success']/$details_info['total']*100), 2);
            }            
            $report_list[] = $details_info;
            
            $report_summary['total_request'] = ($report_summary['total_request'] + $details_info['total']);
            $report_summary['total_pending'] = ($report_summary['total_pending'] + $details_info['pending']);
            $report_summary['total_processed'] = ($report_summary['total_processed'] + $details_info['processed']);
            $report_summary['total_success'] = ($report_summary['total_success'] + $details_info['success']);
            $report_summary['total_failed'] = ($report_summary['total_failed'] + $details_info['failed']);
            $report_summary['total_cancelled'] = ($report_summary['total_cancelled'] + $details_info['cancelled']);
        }
        $report_summary['total_request'] = ($report_summary['total_pending'] + $report_summary['total_processed'] + $report_summary['total_success'] + $report_summary['total_failed'] + $report_summary['total_cancelled']);
        if($report_summary['total_request'] > 0)
        {
            $report_summary['total_ratio_success'] = round((float)($report_summary['total_success']/$report_summary['total_request']*100), 2);
        }
        $report_information['report_list'] = $report_list;
        $report_information['report_summary'] = $report_summary;        
        return $report_information;
    }

    public function get_user_profit_loss($status_id_list = array(), $service_id_list = array(), $start_date, $end_date, $limit, $offset) {
        $profit_loss_infomation = array();
        $start_time = 0;
        $end_time = 0;
        if ($start_date != 0 && $end_date != 0) {
            $this->load->library('Date_utils');
            $start_time = $this->date_utils->server_start_unix_time_of_date($start_date);
            $end_time = $this->date_utils->server_end_unix_time_of_date($end_date);
        }

        if (!empty($where)) {
            $this->cost_profit_model->where($where);
        }
        $report_status_list = array();
        $success_status_report_array = $this->cost_profit_model->get_user_status_report(array(TRANSACTION_STATUS_ID_SUCCESSFUL), $service_id_list, $start_time, $end_time, $limit, $offset)->result_array();
        foreach ($success_status_report_array as $report_status_info) {
            $report_status_list[$report_status_info['service_id']] = $report_status_info['total_status_request'];
        }
        $profit_loss_array = $this->cost_profit_model->get_user_profit_loss($status_id_list, $service_id_list, $start_time, $end_time, $limit, $offset)->result_array();
        $profit_loss_list = array();
        $profit_loss_summary = array();
        $profit_loss_summary['total_request'] = 0;
        $profit_loss_summary['total_status_request'] = 0;
        $profit_loss_summary['total_amount'] = 0;
        $profit_loss_summary['total_used_amount'] = 0;
        $profit_loss_summary['total_profit'] = 0;
        foreach ($profit_loss_array as $profit_loss_info) {
            $profit_loss_info['total_status_request'] = 0;
            if (!empty($report_status_list)&& $profit_loss_info['service_id'] == TRANSACTION_STATUS_ID_SUCCESSFUL) {
                $profit_loss_info['total_status_request'] = $report_status_list[$profit_loss_info['service_id']];
            }
            $profit_loss_info['total_amount'] = $profit_loss_info['total_used_amount'] + $profit_loss_info['total_profit'];
            $profit_loss_list[] = $profit_loss_info;
            $profit_loss_summary['total_request'] = $profit_loss_summary['total_request'] + $profit_loss_info['total_request'];
            $profit_loss_summary['total_status_request'] = $profit_loss_summary['total_status_request'] + $profit_loss_info['total_status_request'];
            $profit_loss_summary['total_amount'] = $profit_loss_summary['total_amount'] + $profit_loss_info['total_amount'];
            $profit_loss_summary['total_used_amount'] = $profit_loss_summary['total_used_amount'] + $profit_loss_info['total_used_amount'];
            $profit_loss_summary['total_profit'] = $profit_loss_summary['total_profit'] + $profit_loss_info['total_profit'];
        }
        $profit_loss_infomation['report_list'] = $profit_loss_list;
        $profit_loss_infomation['report_summary'] = $profit_loss_summary;
        return $profit_loss_infomation;
    }
    
    /*
     * This method will return profit list after rounding profit
     * @param $user_id, user id
     * @author nazmul hasan on 28th november 2016
     */
    public function get_user_service_profits($user_id)
    {
        $profit_list = array();
        $user_profit_array = $this->cost_profit_model->get_user_service_profits($user_id)->result_array();
        foreach($user_profit_array as $profit_info)
        {
            $profit_info['total_profit'] = round($profit_info['total_profit'], 2);
            $profit_list[] = $profit_info;
        }
        return $profit_list;
    }

}

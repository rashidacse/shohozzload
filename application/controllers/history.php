<?php

class History extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('transaction_library');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        redirect('history/all_transactions', 'refresh');
    }

    /*
     * This method will return entire transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function all_transactions() {
        $this->data['message'] = "";
        $user_id = $this->session->userdata('user_id');
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        if (file_get_contents("php://input") != null) {
            $response = array();
            $user_id_list = array($user_id);
            $from_date = 0;
            $to_date = 0;
            $cell_no = '';
            $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "userId") != FALSE && $search_param->userId != '' & $search_param->userId > 0) {
                    $user_id_list = array($search_param->userId);
                }
                if (property_exists($search_param, "cellNo") != FALSE) {
                    $cell_no = $search_param->cellNo;
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
            }
            if($cell_no != '')
            {
                $user_id_list = $this->reseller_library->get_successor_id_list($user_id, true);
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list($user_id_list, array(), $status_id_list, $cell_no, $from_date, $to_date, $limit, $offset);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(), $status_id_list, '', $current_date, $current_date, TRANSACTION_PAGE_DEFAULT_LIMIT, TRANSACTION_PAGE_DEFAULT_OFFSET);
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id);
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/index', $this->data);
    }

    /*
     * This method will return topup transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function topup_transactions($user_id = 0) {
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $user_id = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $cell_no = '';
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE && $search_param->userId != '' & $search_param->userId > 0) {
                    $user_id_list = array($search_param->userId);
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
                if (property_exists($search_param, "cellNo") != FALSE) {
                    $cell_no = $search_param->cellNo;
                }
            }
            //checking user id permission for post param
            if ($user_id != $current_user_id) {
                $this->load->library('reseller_library');
                $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
                if (!in_array($user_id, $successor_id_list)) {
                    //you don't have permission to see transactions of this user
                    $response['error_message'] = "Sorry !! You don't have permission to show any information of this user.";
                    return;
                }
            }
            $user_id_list = array($user_id);
            if($cell_no != '')
            {
                $user_id_list = $this->reseller_library->get_successor_id_list($user_id, true);
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list($user_id_list, array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), $status_id_list, $cell_no, $from_date, $to_date, $limit, $offset);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), $status_id_list, 0, $current_date, $current_date, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id);
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/topup/index', $this->data);
    }

    /*
     * This method will return bkash transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function bkash_transactions($user_id = 0) {
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $user_id = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this transcations.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $cell_no = '';
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE && $search_param->userId != '' & $search_param->userId > 0) {
                    $user_id = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
                if (property_exists($search_param, "cellNo") != FALSE) {
                    $cell_no = $search_param->cellNo;
                }
            }
            //checking user id permission for post param
            if ($user_id != $current_user_id) {
                $this->load->library('reseller_library');
                $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
                if (!in_array($user_id, $successor_id_list)) {
                    //you don't have permission to see transactions of this user
                    $response['error_message'] = "Sorry !! You don't have permission to show any information of this user.";
                    return;
                }
            }
            $user_id_list = array($user_id);
            if($cell_no != '')
            {
                $user_id_list = $this->reseller_library->get_successor_id_list($user_id, true);
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list($user_id_list, array(SERVICE_TYPE_ID_BKASH_CASHIN, SERVICE_TYPE_ID_BKASH_CASHOUT), $status_id_list, $cell_no, $from_date, $to_date, $limit, $offset);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id);
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_BKASH_CASHIN, SERVICE_TYPE_ID_BKASH_CASHOUT), $status_id_list, 0, $current_date, $current_date, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/bkash/index', $this->data);
    }

    /*
     * This method will return dbbl transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function dbbl_transactions($user_id = 0) {
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $user_id = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $cell_no = '';
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE && $search_param->userId != '' & $search_param->userId > 0) {
                    $user_id = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
                if (property_exists($search_param, "cellNo") != FALSE) {
                    $cell_no = $search_param->cellNo;
                }
            }
            //checking user id permission for post param
            if ($user_id != $current_user_id) {
                $this->load->library('reseller_library');
                $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
                if (!in_array($user_id, $successor_id_list)) {
                    //you don't have permission to see transactions of this user
                    $response['error_message'] = "Sorry !! You don't have permission to show any information of this user.";
                    return;
                }
            }
            $user_id_list = array($user_id);
            if($cell_no != '')
            {
                $user_id_list = $this->reseller_library->get_successor_id_list($user_id, true);
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list($user_id_list, array(SERVICE_TYPE_ID_DBBL_CASHIN), $status_id_list, $cell_no, $from_date, $to_date, $limit, $offset);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id);
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_DBBL_CASHIN), $status_id_list, 0, $current_date, $current_date, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/dbbl/index', $this->data);
    }

    /*
     * This method will return mcash transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function mcash_transactions($user_id = 0) {
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $user_id = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $cell_no = '';
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE && $search_param->userId != '' & $search_param->userId > 0) {
                    $user_id = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
                if (property_exists($search_param, "cellNo") != FALSE) {
                    $cell_no = $search_param->cellNo;
                }
            }
            //checking user id permission for post param
            if ($user_id != $current_user_id) {
                $this->load->library('reseller_library');
                $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
                if (!in_array($user_id, $successor_id_list)) {
                    //you don't have permission to see transactions of this user
                    $response['error_message'] = "Sorry !! You don't have permission to show any information of this user.";
                    return;
                }
            }
            $user_id_list = array($user_id);
            if($cell_no != '')
            {
                $user_id_list = $this->reseller_library->get_successor_id_list($user_id, true);
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list($user_id_list, array(SERVICE_TYPE_ID_MCASH_CASHIN), $status_id_list, $cell_no, $from_date, $to_date, $limit, $offset);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id);
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_MCASH_CASHIN), $status_id_list, 0, $current_date, $current_date, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/mcash/index', $this->data);
    }

    /*
     * This method will return ucash transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function ucash_transactions($user_id = 0) {
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $user_id = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
        if (file_get_contents("php://input") != null) {
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $cell_no = '';
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE && $search_param->userId != '' & $search_param->userId > 0) {
                    $user_id = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
                if (property_exists($search_param, "cellNo") != FALSE) {
                    $cell_no = $search_param->cellNo;
                }
            }
            //checking user id permission for post param
            if ($user_id != $current_user_id) {
                $this->load->library('reseller_library');
                $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
                if (!in_array($user_id, $successor_id_list)) {
                    //you don't have permission to see transactions of this user
                    $response['error_message'] = "Sorry !! You don't have permission to show any information of this user.";
                    return;
                }
            }
            $user_id_list = array($user_id);
            if($cell_no != '')
            {
                $user_id_list = $this->reseller_library->get_successor_id_list($user_id, true);
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list($user_id_list, array(SERVICE_TYPE_ID_UCASH_CASHIN), $status_id_list, $cell_no, $from_date, $to_date, $limit, $offset);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id);
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_UCASH_CASHIN), $status_id_list, 0, $current_date, $current_date, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/ucash/index', $this->data);
    }

    /*
     * This method will show sms history
     * @author nazmul hasan on 30th March 2016
     */

    public function sms_transactions($user_id = 0) {
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $current_user_id == $user_id) {
            $user_id = $current_user_id;
        } else {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
        }
        if (file_get_contents("php://input") != null) {
            $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE) {
                    $user_id = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
            }
            $transction_information_array = $this->transaction_library->get_user_sms_transaction_list($status_id_list, $from_date, $to_date, $limit, $offset, array('user_id' => $user_id));
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $transaction_list_array = $this->transaction_library->get_user_sms_transaction_list($status_id_list, $current_date, $current_date, TRANSACTION_PAGE_DEFAULT_LIMIT, TRANSACTION_PAGE_DEFAULT_OFFSET, array('user_id' => $user_id));
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['user_id'] = $user_id;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/sms/index', $this->data);
    }

    /*
     * This method will display payment history
     * @author nazmul hasan on 3rd march 
     */

    public function get_payment_history($user_id = 0) {
        $this->load->library('payment_library');
        if($user_id == 0)
        {
            $user_id = $this->session->userdata('user_id');
        }
        $where = array(
            'reference_id' => $user_id
        );
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $offset = PAYMENT_LIST_DEAFULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = PAYMENT_LIST_DEAFULT_LIMIT;
            $response = array();
            $start_date = 0;
            $end_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $payment_type_id_list = array();
            $all_successors = 0;
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "userId") != FALSE) {
                    $user_id = $search_param->userId;
                }
                if (property_exists($search_param, "allSuccessors") != FALSE) {
                    $all_successors = $search_param->allSuccessors;
                }
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $start_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $end_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
                if (property_exists($search_param, "paymentTypeId") != FALSE && $search_param->paymentTypeId != '0') {
                    $payment_type_id_list = $search_param->paymentTypeId;
                } else {
                    $payment_type_id_list = array(
                        PAYMENT_TYPE_ID_SEND_CREDIT,
                        PAYMENT_TYPE_ID_RETURN_CREDIT,
                        PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
                    );
                }
            }
            $current_user_id = $this->session->userdata('user_id');
            if($user_id == 0)
            {
                $user_id = $current_user_id;
            }
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if ($user_id != $current_user_id && !in_array($user_id, $successor_id_list)) {
                $response['total_transactions'] = 0;
                $response['total_amount'] = 0;
                $response['payment_info_list'] = array();
                echo json_encode($response);
                return;
            }
            $reference_id_list = array();
            $where = array();
            if($all_successors == 1)
            {
                $reference_id_list = array_merge($successor_id_list, array($current_user_id));
            }
            else
            {
                $where = array(
                    'reference_id' => $user_id
                );
            }
            $payment_info_list = $this->payment_library->get_payment_history($payment_type_id_list, $status_id_list, $start_date, $end_date, $limit, $offset, 'desc', $where, array(), $reference_id_list);
            if (!empty($payment_info_list)) {
                $response['total_transactions'] = $payment_info_list['total_transactions'];
                $response['total_amount'] = $payment_info_list['total_amount_in'];
                $response['payment_info_list'] = $payment_info_list['payment_list'];
            }
            echo json_encode($response);
            return;
        }
        $current_user_id = $this->session->userdata('user_id');
        $this->load->library('reseller_library');
        $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
        if ($user_id != $current_user_id && !in_array($user_id, $successor_id_list)) {
            //you don't have permission to view details of this user
            $this->data['app'] = RESELLER_APP;
            $this->data['error_message'] = "Sorry !! You don't have permission to view payment history of this user.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $payment_type_id_list = array(
            PAYMENT_TYPE_ID_SEND_CREDIT,
            PAYMENT_TYPE_ID_RETURN_CREDIT,
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
        );
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $payment_list_array = $this->payment_library->get_payment_history($payment_type_id_list, $status_id_list, $current_date, $current_date, PAYMENT_LIST_DEAFULT_LIMIT, $offset, 'desc', $where);
        $total_transactions = 0;
        $total_amount = 0;
        if (!empty($payment_list_array)) {
            $total_transactions = $payment_list_array['total_transactions'];
            $total_amount = (float)($payment_list_array['total_amount_in']);
            $payment_list = $payment_list_array['payment_list'];
        }
        else
        {
            $total_transactions = 0;
            $total_amount = 0;
            $payment_list = array();
        }
        $payment_types = array(
            PAYMENT_TYPE_ID_SEND_CREDIT => 'Send credit',
            PAYMENT_TYPE_ID_RETURN_CREDIT => 'Return Credit',
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT => 'Return Credit Back',
            '0' => 'All'
        );
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id, true);
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['payment_type_ids'] = $payment_types;
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['payment_info_list'] = json_encode($payment_list);
        $this->data['user_id'] = $user_id;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/payment_history', $this->data);
    }

    /*
     * This method will display receive history
     * @author nazmul hasan on 3rd march 
     */

    public function get_receive_history($user_id = 0) {
        if($user_id == 0)
        {
            $user_id = $this->session->userdata('user_id');
        }
        $current_user_id = $this->session->userdata('user_id');
        $group = "";
        if($user_id == $current_user_id)
        {
            $groups = $this->ion_auth->get_current_user_types();        
            foreach ($groups as $group_info) {
                if ($group_info == GROUP_ADMIN) {
                    $group = $group_info;
                    break;
                }
            }
        }
        
        $this->load->library('payment_library');
        //$user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $offset = PAYMENT_LIST_DEAFULT_OFFSET;
        $limit = PAYMENT_LIST_DEAFULT_LIMIT;
        if (file_get_contents("php://input") != null) {
            $response = array();
            $start_date = 0;
            $end_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $payment_type_id_list = array();
            $all_successors = 0;
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "userId") != FALSE) {
                    $user_id = $search_param->userId;
                }
                if (property_exists($search_param, "allSuccessors") != FALSE) {
                    $all_successors = $search_param->allSuccessors;
                }
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $start_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $end_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
                if (property_exists($search_param, "paymentTypeId") != FALSE && $search_param->paymentTypeId != '0') {
                    $payment_type_id_list = $search_param->paymentTypeId;
                } else {
                    $payment_type_id_list = array(
                        PAYMENT_TYPE_ID_SEND_CREDIT,
                        PAYMENT_TYPE_ID_RETURN_CREDIT,
                        PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
                    );
                    if ($group == GROUP_ADMIN) {
                        $payment_type_id_list[] = PAYMENT_TYPE_ID_LOAD_BALANCE;
                    }
                }
            }
            $current_user_id = $this->session->userdata('user_id');
            if($user_id == 0)
            {
                $user_id = $current_user_id;
            }
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if ($user_id != $current_user_id && !in_array($user_id, $successor_id_list)) {
                $response['total_transactions'] = 0;
                $response['total_amount'] = 0;
                $response['payment_info_list'] = array();
                echo json_encode($response);
                return;
            }
            $user_id_list = array();
            $where = array();
            if($all_successors == 1)
            {
                $user_id_list = array_merge($successor_id_list, array($current_user_id));
            }
            else
            {
                $where = array(
                    'user_id' => $user_id
                );
            }
            $payment_info_list = $this->payment_library->get_payment_history($payment_type_id_list, $status_id_list, $start_date, $end_date, $limit, $offset, 'desc', $where, $user_id_list, array());
            if (!empty($payment_info_list)) {
                $response['total_transactions'] = $payment_info_list['total_transactions'];
                $response['total_amount'] = $payment_info_list['total_amount_in'];
                $response['payment_info_list'] = $payment_info_list['payment_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('reseller_library');
        $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
        if ($user_id != $current_user_id && !in_array($user_id, $successor_id_list)) {
            //you don't have permission to view details of this user
            $this->data['app'] = RESELLER_APP;
            $this->data['error_message'] = "Sorry !! You don't have permission to view receive history of this user.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $payment_types = array(
            PAYMENT_TYPE_ID_SEND_CREDIT => 'Send credit',
            PAYMENT_TYPE_ID_RETURN_CREDIT => 'Return Credit',
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT => 'Return Credit Back',
            '0' => 'All'
        );
        $payment_type_id_list = array(
            PAYMENT_TYPE_ID_SEND_CREDIT,
            PAYMENT_TYPE_ID_RETURN_CREDIT,
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
        );
        if ($group == GROUP_ADMIN) {
            $payment_type_id_list[] = PAYMENT_TYPE_ID_LOAD_BALANCE;
            $payment_types[PAYMENT_TYPE_ID_LOAD_BALANCE] = 'Load Balance';
        }
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $payment_list_array = $this->payment_library->get_payment_history($payment_type_id_list, $status_id_list, $current_date, $current_date, PAYMENT_LIST_DEAFULT_LIMIT, $offset, 'desc', $where);
        $total_transactions = 0;
        $total_amount = 0;
        if (!empty($payment_list_array)) {
            $total_transactions = $payment_list_array['total_transactions'];
            $total_amount = (float)($payment_list_array['total_amount_in']);
            $payment_list = $payment_list_array['payment_list'];
        }
        else
        {
            $total_transactions = 0;
            $total_amount = 0;
            $payment_list = array();
        }
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id, true);
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['payment_type_ids'] = $payment_types;
        $this->data['payment_info_list'] = json_encode($payment_list);
        $this->data['user_id'] = $user_id;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/receive_history', $this->data);
    }

    /*
     * This method will load pagination template
     * @author rashida on 26th April 2016 
     */

    function pagination_tmpl_load() {
        $this->load->view('dir_pagination');
    }

    // ----------------------------------- Pending Request --------------------------------//
    /*
     * This method will display transaction list of pending request from left panel menu item
     * @author nazmul hasan on 8th october 2016
     */
    function pending() {
        $this->data['message'] = "";
        $user_id = $this->session->userdata('user_id');
        //right now we are displaying pending and processed transaction statuses as pending request history page
        $status_id_list = array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_PROCESSED, TRANSACTION_STATUS_ID_FAILED);
        if (file_get_contents("php://input") != null) {
            $response = array();
            $user_id_list = array($user_id);
            $from_date = 0;
            $to_date = 0;
            $cell_no = '';
            $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    }
                }
                if (property_exists($search_param, "userId") != FALSE && $search_param->userId != '' & $search_param->userId > 0) {
                    $user_id_list = array($search_param->userId);
                }
                if (property_exists($search_param, "cellNo") != FALSE) {
                    $cell_no = $search_param->cellNo;
                }
            }
            if($cell_no != '')
            {
                $user_id_list = $this->reseller_library->get_successor_id_list($user_id, true);
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list($user_id_list, array(), $status_id_list, $cell_no, $from_date, $to_date, $limit, $offset);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(), $status_id_list, '', $current_date, $current_date, TRANSACTION_PAGE_DEFAULT_LIMIT, TRANSACTION_PAGE_DEFAULT_OFFSET);
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->load->library('reseller_library');
        $this->data['successor_group_list'] = $this->reseller_library->get_user_successor_group_list($user_id);
        $transction_status_list = $this->transaction_library->get_pending_request_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/pending/index', $this->data);
    }

}

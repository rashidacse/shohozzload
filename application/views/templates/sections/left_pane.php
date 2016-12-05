<!--<ul id="navmenu">
    <li class="home"><a href="<?php echo base_url(); ?>" id="homepage" class="top">Dashboard</a></li>
    <li>
        <a class="chld" href="javascript:void(0)">New Request</a>
        <ul id="baby">

            <li ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"><a href="<?php echo base_url() . 'transaction/topup' ?>">Topup</a></li>
            <li><a href="#">Bulk Flexiload</a></li>
            <div ng-repeat="service in serviceList">
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/bkash' ?>" >bKash</a></li>
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/dbbl' ?>">DBBL</a></li>
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/mcash' ?>">M-Cash</a></li>
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/ucash' ?>">U-Cash</a></li>
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS; ?>"><a href="<?php echo base_url() . 'history/sms_transactions' ?>">Send SMS</a></li>
            </div>
            <li><a href="#">Global Topup</a></li>
        </ul>
    </li>

    <li><a href="<?php echo base_url(); ?>history/pending">Pending Request</a></li>
    <li><a href="javascript:void(0)" class="chld">History</a>
        <ul id="baby">
            <li><a href="<?php echo base_url() . 'history/all_transactions' ?>">All History</a></li>
            <li  ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"> <a href ="<?php echo base_url() . 'history/topup_transactions' ?>">Topup</a></li>						
            <div ng-repeat="service in serviceList">
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/bkash_transactions' ?>">bKash</a></li>						
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'history/dbbl_transactions' ?>">DBBL</a></li>						
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/mcash_transactions' ?>">M-Cash</a></li>						
                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/ucash_transactions' ?>">U-Cash</a></li>	
            </div>					
        </ul>
    </li>

    <li><a href="<?php echo base_url() . 'reseller/get_reseller_list' ?>">Resellers</a></li>		
    <li><a href="<?php echo base_url(); ?>history/get_payment_history">Payment History</a></li>
    <li><a href="<?php echo base_url(); ?>history/get_receive_history">Receive History</a></li>		
    <li><a href="javascript:void(0)" class="chld">Report </a>
        <ul id="baby">
            <li><a href="<?php echo base_url() . 'report/get_cost_and_profit' ?>">Cost &amp; Profit</a></li>
            <li><a href="<?php echo base_url() . 'report/get_balance_report' ?>">Balance Report</a></li>
            <li><a href="<?php echo base_url() . 'report/get_total_report' ?>">Total Report</a></li>
            <li><a href="<?php echo base_url() . 'report/get_detailed_report' ?>">Detailed Report</a></li>
            <li><a href="<?php echo base_url() . 'report/get_user_profit_loss' ?>">Profit/Loss Analysis</a></li>
        </ul>
    </li>
    <li><a href="javascript:void(0)" class="chld">My Account </a>
        <ul id="baby">
            <li><a href="<?php echo base_url(); ?>reseller/show_user_rate">My Rates</a></li>
            <li><a href="#">API Key</a></li>
            <li><a href="<?php echo base_url(); ?>payment/reseller_return_balance">Return Balance</a></li>
            <li><a href="<?php echo base_url(); ?>reseller/show_user_profile">My Profile</a></li>
                                <li><a href="#">Access Logs</a></li>
                                <li><a href="#">Change Pin</a></li>                
                                <li><a href="#">Change Password</a></li>
        </ul>
    </li>
    <li><a href="#">Complain </a></li>
    <li><a href="<?php echo base_url() . 'auth/logout' ?>">
            <img src="<?php echo base_url(); ?>resources/images/logout.png"> 
            <b>Logout</b>
        </a>
    </li>

</ul>-->
<div  class="left_menu form-group"  ng-controller="leftController">
    <div id="set_user_service_id" ng-init="setServiceList('<?php echo htmlspecialchars(json_encode($my_service_list)) ?>')">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url(); ?>" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            Dashboard
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <div class="panel-heading custom-panel-heading" role="tab" id="headingTwo" class="collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <h4 class="panel-title">
                        <a class="left-menu-anchor" role="button">New Request</a> <img class="pull-right" src="<?php echo base_url(); ?>resources/images/down-arrow.png"> 
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <ul class="left-menu-unorder-list">
                        <a href="<?php echo base_url() . 'transaction/topup' ?>"><li ng-if="'<?php echo $topup_service_allow_flag; ?>' != false">Topup</li></a>
                        <!--<a href="#"><li>Bulk Flexiload</li></a>-->
                        <div class="" ng-repeat="service in serviceList">
                            <a href="<?php echo base_url() . 'transaction/bkash' ?>" ><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>">bKash</li></a>
                            <a href="<?php echo base_url() . 'transaction/dbbl' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>">DBBL</li></a>
                            <a href="<?php echo base_url() . 'transaction/mcash' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>">M-Cash</li></a>
                            <a href="<?php echo base_url() . 'transaction/ucash' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>">U-Cash</li></a>
                            <a href="<?php echo base_url() . 'transaction/sms' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS; ?>">Send SMS</li></a>
                        </div>
                        <!--<li><a href="#">Global Topup</a></li>-->
                    </ul>
                </div>
            </div>
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url(); ?>history/pending" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingThree">
                        <h4 class="panel-title">
                            Pending Request
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <div class="panel-heading custom-panel-heading" role="tab" id="headingFour" class="collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    <h4 class="panel-title">
                        <a class="left-menu-anchor" role="button">History</a> <img class="pull-right" src="<?php echo base_url(); ?>resources/images/down-arrow.png">
                    </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                    <ul class="left-menu-unorder-list">
                        <a href="<?php echo base_url() . 'history/all_transactions' ?>"><li>All History</li></a>
                        <a href ="<?php echo base_url() . 'history/topup_transactions' ?>"><li  ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"> Topup</li></a>						
                        <div ng-repeat="service in serviceList">
                            <a href="<?php echo base_url() . 'history/bkash_transactions' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>">bKash</li></a>						
                            <a href="<?php echo base_url() . 'history/dbbl_transactions' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>">DBBL</li></a>						
                            <a href="<?php echo base_url() . 'history/mcash_transactions' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>">M-Cash</li></a>					
                            <a href="<?php echo base_url() . 'history/ucash_transactions' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>">U-Cash</li></a>
                            <a href="<?php echo base_url() . 'history/sms_transactions' ?>"><li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS; ?>">Send SMS</li></a>	
                        </div>                    				
                    </ul>
                </div>
            </div>
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url() . 'reseller/get_reseller_list' ?>" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingFive">
                        <h4 class="panel-title">
                            Resellers
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url(); ?>history/get_payment_history" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingSix">
                        <h4 class="panel-title">
                            Payment History
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url(); ?>history/get_receive_history" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingSeven">
                        <h4 class="panel-title">
                            Receive History
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <div class="panel-heading custom-panel-heading" role="tab" id="headingEight" class="collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                    <h4 class="panel-title">
                        <a class="left-menu-anchor" role="button">Report</a> <img class="pull-right" src="<?php echo base_url(); ?>resources/images/down-arrow.png">
                    </h4>
                </div>
                <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                    <ul class="left-menu-unorder-list">
                        <a href="<?php echo base_url() . 'report/get_cost_and_profit' ?>"><li>Cost &amp; Profit</li></a>
                        <a href="<?php echo base_url() . 'report/get_balance_report' ?>"><li>Balance Report</li></a>
                        <a href="<?php echo base_url() . 'report/get_total_report' ?>"><li>Total Report</li></a>
                        <a href="<?php echo base_url() . 'report/get_detailed_report' ?>"><li>Detailed Report</li></a>
                        <a href="<?php echo base_url() . 'report/get_user_profit_loss' ?>"><li>Profit/Loss Analysis</li></a>
                    </ul>
                </div>
            </div>
            <div class="panel panel-default custom-panel">
                <div class="panel-heading custom-panel-heading" role="tab" id="headingNine" class="collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                    <h4 class="panel-title">
                        <a class="left-menu-anchor" role="button">My Account</a> <img class="pull-right" src="<?php echo base_url(); ?>resources/images/down-arrow.png">
                    </h4>
                </div>
                <div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNine">
                    <ul class="left-menu-unorder-list">
                        <a href="<?php echo base_url(); ?>reseller/update_rate"><li>My Rates</li></a>
                        <!--<li><a href="#">API Key</a></li>-->
                        <a href="<?php echo base_url(); ?>admin/load_balance"><li>Add Balance</li></a>
                        <a href="<?php echo base_url(); ?>reseller/show_user_profile"><li>My Profile</li></a>
                        <!--                        <li><a href="#">Access Logs</a></li>
                                                <li><a href="#">Change Pin</a></li>                
                                                <li><a href="#">Change Password</a></li>-->
                    </ul>
                </div>
            </div>

            <!--            <div class="panel panel-default custom-panel">
                            <a class="left-menu-anchor" href="" role="button">
                                <div class="panel-heading custom-panel-heading" role="tab" id="headingTen">
                                    <h4 class="panel-title">
                                        Complain
                                    </h4>
                                </div>
                            </a>
                        </div>-->
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url() . 'auth/logout' ?>" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingEleven">
                        <h4 class="panel-title">
                            <img src="<?php echo base_url(); ?>resources/images/logout.png"> 
                            <b>Logout</b>
                        </h4>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
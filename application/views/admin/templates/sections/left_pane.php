
<div class="left_menu form-group" ng-controller="leftController">
    <div id="set_user_service_id" ng-init="setServiceList('<?php echo htmlspecialchars(json_encode($my_service_list)) ?>')">
        <!--        <ul id="navmenu">
                    <li class="home"><a href="<?php echo base_url(); ?>" onclick="window.location.reload(true);" id="homepage" class="top">Dashboard</a></li>
                    <li>
                        <a class="chld" href="javascript:void(0)">New Request</a>
                        <ul id="baby">
                            <li ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"><a href="<?php echo base_url() . 'transaction/topup' ?>" onclick="window.location.reload(true);">Topup</a></li>
                            <li ng-if="'<?php echo $bkash_service_allow_flag; ?>' != false"><a href="<?php echo base_url() . 'transaction/bkash' ?>" onclick="window.location.reload(true);">bKash</a></li>
                            <li><a href="#">Bulk Flexiload</a></li>
                            <div ng-repeat="service in serviceList">    
                                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/dbbl' ?> " onclick="window.location.reload(true);">DBBL</a></li>
                                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/mcash' ?>" onclick="window.location.reload(true);">M-Cash</a></li>
                                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/ucash' ?>" onclick="window.location.reload(true);">U-Cash</a></li>
                                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS; ?>"><a href="<?php echo base_url() . 'transaction/sms' ?>" onclick="window.location.reload(true);">Send SMS</a></li>
                            </div>
                            <li><a href="#">Global Topup</a></li>
                        </ul>
                    </li>
                    <li ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"><a href="<?php echo base_url(); ?>package_recharge" onclick="window.location.reload(true);">Package Recharge</a></li>
                    <li><a href="<?php echo base_url(); ?>history/pending" onclick="window.location.reload(true);">Pending Request</a></li>
                    <li><a href="javascript:void(0)" class="chld">History</a>
                        <ul id="baby">
                            <li><a href="<?php echo base_url() . 'history/all_transactions' ?>" onclick="window.location.reload(true);">All History</a></li>
                            <li ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"> <a href ="<?php echo base_url() . 'history/topup_transactions' ?>" onclick="window.location.reload(true);">Topup</a></li>						
                            <li ng-if="'<?php echo $bkash_service_allow_flag; ?>' != false"><a href="<?php echo base_url() . 'history/bkash_transactions' ?>" onclick="window.location.reload(true);">bKash</a></li>
                            <div ng-repeat="service in serviceList">
                                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'history/dbbl_transactions' ?>" onclick="window.location.reload(true);">DBBL</a></li>						
                                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/mcash_transactions' ?>" onclick="window.location.reload(true);">M-Cash</a></li>						
                                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/ucash_transactions' ?>" onclick="window.location.reload(true);">U-Cash</a></li>	
                                <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS; ?>"><a href="<?php echo base_url() . 'history/sms_transactions' ?>" onclick="window.location.reload(true);">Send SMS</a></li>	
                            </div>                    				
                        </ul>
                    </li>
        
                    <li><a href="<?php echo base_url() . 'reseller/get_reseller_list' ?>" onclick="window.location.reload(true);">Resellers</a></li>
                    <li><a href="<?php echo base_url(); ?>package" onclick="window.location.reload(true);">Manage Package</a></li>            
                    <li><a href="<?php echo base_url(); ?>history/get_payment_history" onclick="window.location.reload(true);">Payment History</a></li>
                    <li><a href="<?php echo base_url(); ?>history/get_receive_history" onclick="window.location.reload(true);">Receive History</a></li>	
                    <li><a href="javascript:void(0)" class="chld">Report </a>
                        <ul id="baby">
                            <li><a href="<?php echo base_url() . 'report/get_cost_and_profit' ?>">Cost &amp; Profit</a></li>
                            <li><a href="<?php echo base_url() . 'report/get_balance_report' ?>">Balance Report</a></li>
                            <li><a href="<?php echo base_url() . 'report/get_total_report' ?>" onclick="window.location.reload(true);">Total Report</a></li>
                            <li><a href="<?php echo base_url() . 'report/get_detailed_report' ?>" onclick="window.location.reload(true);">Detailed Report</a></li>
                            <li><a href="<?php echo base_url() . 'report/get_user_profit_loss' ?>">Profit/Loss Analysis</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)" class="chld">My Account </a>
                        <ul id="baby">
                            <li><a href="<?php echo base_url(); ?>reseller/update_rate" onclick="window.location.reload(true);">My Rates</a></li>
                            <li><a href="#">API Key</a></li>
                            <li><a href="<?php echo base_url(); ?>admin/load_balance" onclick="window.location.reload(true);">Add Balance</a></li>
                            <li><a href="<?php echo base_url(); ?>reseller/show_user_profile" onclick="window.location.reload(true);">My Profile</a></li>
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
        <!--<div class="clrGap">&nbsp;</div>-->



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
                <a class="left-menu-anchor" href="<?php echo base_url(); ?>package" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingSix">
                        <h4 class="panel-title">
                            Package
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url(); ?>transaction/package_recharge" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingSeven">
                        <h4 class="panel-title">
                            Package Recharge
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url(); ?>history/get_payment_history" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingEight">
                        <h4 class="panel-title">
                            Payment History
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url(); ?>history/get_receive_history" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingNine">
                        <h4 class="panel-title">
                            Receive History
                        </h4>
                    </div>
                </a>
            </div>
            <div class="panel panel-default custom-panel">
                <div class="panel-heading custom-panel-heading" role="tab" id="headingTen" class="collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                    <h4 class="panel-title">
                        <a class="left-menu-anchor" role="button">Report</a> <img class="pull-right" src="<?php echo base_url(); ?>resources/images/down-arrow.png">
                    </h4>
                </div>
                <div id="collapseTen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTen">
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
                <div class="panel-heading custom-panel-heading" role="tab" id="headingEleven" class="collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                    <h4 class="panel-title">
                        <a class="left-menu-anchor" role="button">My Account</a> <img class="pull-right" src="<?php echo base_url(); ?>resources/images/down-arrow.png">
                    </h4>
                </div>
                <div id="collapseEleven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEleven">
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
                            <div class="panel-heading custom-panel-heading" role="tab" id="headingTwelve" class="collapsed"  data-toggle="collapse" data-parent="#accordion" href="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                                <h4 class="panel-title">
                                    <a class="left-menu-anchor" role="button">Tools</a> <img class="pull-right" src="<?php echo base_url(); ?>resources/images/down-arrow.png">
                                </h4>
                            </div>
                            <div id="collapseTwelve" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwelve">
                                <ul class="left-menu-unorder-list">
                                    <a href="<?php echo base_url(); ?>admin/theme_changer"><li>Theme Changer</li></a>
                                </ul>
                            </div>
                        </div>
                        <div class="panel panel-default custom-panel">
                            <a class="left-menu-anchor" href="" role="button">
                                <div class="panel-heading custom-panel-heading" role="tab" id="headingThirteen">
                                    <h4 class="panel-title">
                                        Complain
                                    </h4>
                                </div>
                            </a>
                        </div>-->
            <div class="panel panel-default custom-panel">
                <a class="left-menu-anchor" href="<?php echo base_url() . 'auth/logout' ?>" role="button">
                    <div class="panel-heading custom-panel-heading" role="tab" id="headingFourteen">
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
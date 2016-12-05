<div class="ud">
    <marquee scrollamount="4" onmouseover="this.scrollAmount = 0" onmouseout="this.scrollAmount = 4" behavior="SCROLL" class="marquee">
        <?php echo $user_message; ?>
    </marquee>
</div>
<div  ng-controller="resellerController">
    <div class="row form-group">
        <div class="col-md-offset-1 col-md-10">
    <div class="row form-group">
        <div class="col-md-12">
            <div class="mypage" style="width:100%;float:left;padding-bottom:0px;">
                <ul class="shortcurt" id="set_service_id" ng-init="setServiceList('<?php echo htmlspecialchars(json_encode($my_service_list)) ?>')" >
                    <li ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"><a href="<?php echo base_url() . 'transaction/topup' ?>"><img src="<?php echo base_url(); ?>resources/images/flexiload.png" onerror="this.onerror=null;this.src='resources/images/default.png';">Topup</a></li>						
                    <li ng-if="'<?php echo $bkash_service_allow_flag; ?>' != false"><a href="<?php echo base_url() . 'transaction/bkash' ?>"> <img src="<?php echo base_url(); ?>resources/images/bkash.png" onerror="this.onerror=null;this.src='resources/images/default.png';">bKash</a></li>
                    <div ng-repeat="service in serviceList">                						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/dbbl' ?>"> <img src="<?php echo base_url(); ?>resources/images/dbbl.png" onerror="this.onerror=null;this.src='resources/images/default.png';">DBBL</a></li>						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/mcash' ?>"><img src="<?php echo base_url(); ?>resources/images/mcash.png" onerror="this.onerror=null;this.src='resources/images/default.png';">M-Cash</a></li>						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/ucash' ?>"><img src="<?php echo base_url(); ?>resources/images/ucash.png" onerror="this.onerror=null;this.src='resources/images/default.png';">U-Cash</a></li>
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS; ?>"><a href="<?php echo base_url() . 'transaction/sms' ?>"><img src="<?php echo base_url(); ?>resources/images/sms.png" onerror="this.onerror=null;this.src='resources/images/default.png';">Send SMS</a></li>
                    </div>
        <!--            <li><a href="#"><img src="<?php echo base_url(); ?>resources/images/default.png" onerror="this.onerror=null;this.src='resources/images/default.png';">Global Topup</a></li>						
                    <li><a href="#"><img src="<?php echo base_url(); ?>resources/images/buycard.png">Prepaid Card</a></li>			
                    <li><a href="#"><img src="<?php echo base_url(); ?>resources/images/billpay.png">Bill Payment</a></li>			-->
                    <!--<li><a href="<?php echo base_url() . 'transaction/sms' ?>"><img src="<?php echo base_url(); ?>resources/images/sms.png">Send SMS</a></li>-->

                </ul>
            </div>
        </div>

    </div>
    <!--    <div class="row form-group">
            <div class="col-md-12">
                <div class="mypage" style="width:100%;float:left;">
                    <div class="usage">
                        <h2>Today's Usages</h2>
                        <div class="table">
                            <table cellspacing="0;">
                                <tbody>
    <?php foreach ($today_usages as $key => $value) { ?>
                                                <tr><td><?php echo $key ?></td><td class="tk"></td><td class="tk"><?php echo $value ?></td></tr>
    <?php } ?>                        
                                </tbody></table>
                        </div>
                        <h3><span class="left">Sub-Total</span><span class="right">0.00</span></h3>
                    </div>
                    <div class="usage">
                        <h2>Last Payments</h2>
                        <div class="table">
                            <table cellspacing="0;">
                                <tbody>
    <?php foreach ($payment_list as $payment_info) { ?>
                                                <tr><td><?php echo $payment_info['destination_username']; ?></td><td class="tk"></td><td class="tk"><?php echo $payment_info['amount'] ?></td></tr>
    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <h3><span class="left">Showing last <?php echo DASHBOARD_PAYMENT_LIMIT; ?> records</span><span class="right"><a href="<?php echo base_url() . 'history/get_payment_history' ?>" style="font-size:12px;">[View All]</a></span></h3>
                    </div>
                    <div class="usage">
                        <h2>Last Receive</h2>
                        <div class="table">
                            <table cellspacing="0;">
                                <tbody>
    <?php foreach ($receive_list as $receive_info) { ?>
                                                <tr><td><?php echo $receive_info['source_username']; ?></td><td class="tk"></td><td class="tk"><?php echo $receive_info['amount'] ?></td></tr>
    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <h3><span class="left">Showing last <?php echo DASHBOARD_RECEIVE_LIMIT; ?> records</span><span class="right"><a href="<?php echo base_url() . 'history/get_receive_history' ?>" style="font-size:12px;">[View All]</a></span></h3>
                    </div>
                </div> 
            </div>
        </div>-->



    
            <div class="row form-group">
                <div class="col-md-4 form-group">
                    <div class="table-border table-height-adjust">
                        <h5 class="table-heading">Today's Usages</h5>
                        <table class="table table-responsive table-margin-adjust">
                            <tbody class="table-body-border">
                                <?php foreach ($today_usages as $key => $value) { ?>
                                    <tr>
                                        <td class="table-key"><?php echo $key ?></td>
                                        <td class="table-value"> <?php echo $value ?></td>
                                    </tr>
                                <?php } ?>     
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <div class="table-border table-height-adjust">
                        <h5 class="table-heading">Last Payments</h5>
                        <table class="table table-responsive table-margin-adjust">
                            <tbody class="table-body-border">
                                <?php foreach ($payment_list as $payment_info) { ?>
                                    <tr>
                                        <td class="table-key"><?php echo $payment_info['destination_username']; ?></td>
                                        <td class="table-value"> <?php echo $payment_info['amount'] ?></td>
                                    </tr>
                                <?php } ?>     
                            </tbody>
                        </table>
                    </div>
                    <h5 class="table-footer"><span >Showing last <?php echo DASHBOARD_PAYMENT_LIMIT; ?> records</span><span class="pull-right"><a href="<?php echo base_url() . 'history/get_payment_history' ?>" style="font-size:12px;">[View All]</a></span></h5>
                </div>
                <div class="col-md-4 form-group">
                    <div class="table-border table-height-adjust">
                        <h5 class="table-heading">Last Receive</h5>
                        <table class="table table-responsive table-margin-adjust">
                            <tbody class="table-body-border">
                                <?php foreach ($receive_list as $receive_info) { ?>
                                    <tr>
                                        <td class="table-key"><?php echo $receive_info['source_username']; ?></td>
                                        <td class="table-value"> <?php echo $receive_info['amount'] ?></td>
                                    </tr>
                                <?php } ?>     
                            </tbody>
                        </table>
                    </div>
                    <h5 class="table-footer"><span >Showing last <?php echo DASHBOARD_RECEIVE_LIMIT; ?> records</span><span class="pull-right"><a href="<?php echo base_url() . 'history/get_payment_history' ?>" style="font-size:12px;">[View All]</a></span></h5>
                </div>
            </div>
        </div>
    </div>
</div>
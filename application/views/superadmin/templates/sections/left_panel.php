<div class="left-nav">
    <ul id="left-nav">
        <li id="service">
            <a href="<?php echo base_url() ?>superadmin/service/show_services" onclick="window.location.reload(true);">Services</a>
        </li>
<!--        <li id="subscriber">
            <a href="<?php echo base_url() ?>superadmin/subscriber/show_subscribers">Subscribers</a>
        </li>
        <li id="payment">
            <a href="<?php echo base_url() ?>superadmin/payment/show_payments">Payments</a>
        </li>-->

        <li id="transaction">
            <a href="<?php echo base_url() ?>superadmin/transaction/get_transaction_list" onclick="window.location.reload(true);">Transactions</a>
        </li>
        <li id="sim">
            <a href="<?php echo base_url() ?>superadmin/sim" onclick="window.location.reload(true);">Sims</a>
        </li>
        <li id="sms">
            <a href="<?php echo base_url() ?>superadmin/sim/get_sms_list" onclick="window.location.reload(true);">SMS</a>
        </li>
        <li >
            <a href="<?php echo base_url() ?>superadmin/company_info_configuration" onclick="window.location.reload(true);">Company info configure</a>
        </li>
        <li >
            <a href="<?php echo base_url() ?>superadmin/login_attempt" onclick="window.location.reload(true);">Login attempt</a>
        </li>
        <li id="user">
            <a href="<?php echo base_url() ?>superadmin/user/update_user" onclick="window.location.reload(true);">Update User</a>
        </li>
        <li id="logout">
            <a href="<?php echo base_url() ?>superadmin/auth/logout">Log out</a>                                
        </li>                            
    </ul>
</div>
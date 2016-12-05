<script type="text/javascript">
    $(function () {
        $('#start_date').datepicker().on('changeDate', function(ev) {
            $('#start_date').text($('#start_date').data('date'));
            $('#start_date').datepicker('hide');
        });
        $('#end_date').datepicker().on('changeDate', function(ev) {
            $('#end_date').text($('#end_date').data('date'));
            $('#end_date').datepicker('hide');
        });
//        $('#start_date').Zebra_DatePicker();
        $('#start_date').val('<?php echo $current_date ?>');
//        $('#end_date').Zebra_DatePicker();
        $('#end_date').val('<?php echo $current_date ?>');
        $('#repeatSelect').val('<?php echo TRANSACTION_STATUS_ID_PENDING ?>');
    });
   
    function search_transaction(searchInfo) {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getTransactionList(startDate, endDate);
    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Transaction History</span></div>
<div class="mypage" ng-controller="transctionController" ng-init="initTransactionsHistory('<?php echo htmlspecialchars($current_date) ?>', '<?php echo htmlspecialchars($current_date) ?>', '<?php echo htmlspecialchars(json_encode($service_list)) ?>', '<?php echo htmlspecialchars(json_encode($transction_status_list)) ?>', '<?php echo htmlspecialchars(json_encode($transaction_process_type_list)) ?>')">
    <ul class="list-unstyled paymentHistorySearch">
        <li>Start Date</li>
        <li><input data-date-format='yyyy-mm-dd' id="start_date" type="text" size="18" placeholder="Start Date"  name="start_date" class="form-control input-xs customInputMargin"></li>
        <li>End Date</li>
        <li><input data-date-format='yyyy-mm-dd' id="end_date" type="text" size="18" placeholder="End Date"  name="end_date" class="form-control input-xs customInputMargin"></li>
        <li>Cell No</li>
        <li> <input type="text" class="form-control input-xs customInputMargin" placeholder="017XXXXXXXX" ng-model="searchInfo.cellNo"></li>
        <li>Service </li>
        <li>
             <select  ng-model='searchInfo.serviceId' required ng-options='service.id as service.title for service in serviceList' class="form-control input-xs"></select>
        </li>
        <li>Status Type</li>
        <li> 
            <select  ng-model='searchInfo.statusId' required ng-options='transactionStatus.id as transactionStatus.title for transactionStatus in transactionStatusList' class="form-control input-xs"></select>
        </li>
        <li>
            Process Type</li>
        <li> 
            <select  ng-model='searchInfo.processId' required ng-options='transactionProcessType.id as transactionProcessType.title for transactionProcessType in transactionProcessTypeList' class="form-control input-xs"></select>
        </li>
        <li>Show All</li>
        <li> <input type="checkbox" ng-model="allTransactions"></li>
        <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="search_transaction(angular.element(this).scope().searchInfo)" class="button-custom"></li>
    </ul>
    <table class="table table-striped table-hover"> 
        <thead>
            <tr>
                <th><a href="">Id</a></th>
                <th><a href="">Number</a></th>
                <th><a href="">Type</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Cost</a></th>
                <th><a href="">Title</a></th>
                <th><a href="">User</a></th>
                <th><a href="">Transaction Id</a></th>                
                <th><a href="">Sender</a></th>                
                <th><a href="">Status</a></th>
                <th><a href="">Process</a></th>
                <th><a href="">Date</a></th>
                <th><a href="">Action</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot ng-init="setTransctionList('<?php echo htmlspecialchars(json_encode($transaction_list)) ?>', '<?php echo htmlspecialchars(json_encode($total_transactions)) ?>', '<?php echo htmlspecialchars(json_encode($total_amount)) ?>')">
            <tr ng-repeat="transctionInfo in transctionInfoList">
                <th>{{transctionInfo.t_id}}</th>
                <th>{{transctionInfo.cell_no}}</th>
                <th>{{transctionInfo.type}}</th>
                <th>{{transctionInfo.amount}}</th>
                <th>{{transctionInfo.cost}}</th>
                <th>{{transctionInfo.service_title}}</th>
                <th>{{transctionInfo.username}}</th>
                <th>{{transctionInfo.trx_id_operator}}</th>                
                <th>{{transctionInfo.sender_cell_no}}</th>
                
                
                <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_PENDING; ?>" style="color: violet">{{transctionInfo.status}}</th>
                <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_SUCCESSFUL; ?>" style="color: green">{{transctionInfo.status}}</th>
                <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_FAILED; ?>" style="color: red">{{transctionInfo.status}}</th>
                <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_CANCELLED; ?>" style="color: brown">{{transctionInfo.status}}</th>
                <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_PROCESSED; ?>" style="color: blue">{{transctionInfo.status}}</th>

                
                <th ng-if="transctionInfo.process_type_id == '<?php echo TRANSACTION_PROCESS_TYPE_ID_AUTO; ?>'">Auto</th>
                <th ng-if="transctionInfo.process_type_id == '<?php echo TRANSACTION_PROCESS_TYPE_ID_MANUAL; ?>'">Manual</th>
                <th>{{transctionInfo.created_on}}</th>
                <th><a href="<?php echo base_url() . "superadmin/transaction/update_transaction/"; ?>{{transctionInfo.transaction_id}}">Edit</a></th>
            </tr>
        </tfoot>
    </table>
    <div class="form-group">
        <div class="col-md-12 col-md-offset-4">
            <div class="summery">
                <p></p>
                <table>
                    <tbody>
                        <tr><td>Current Page Amount :</td><td class="amt">{{currentPageAmount}}</td></tr>
                        <tr><td>Total Amount :</td><td class="amt">{{totalAmount}}</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
    <li style="display: none" dir-paginate="transactionInfo in transctionInfoList|itemsPerPage:pageSize" current-page="currentPage"></li>
    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getTransactionByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>superadmin/transaction/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div>

<?php
//$this->load->view("superadmin/transaction/modal_delete_transaction");

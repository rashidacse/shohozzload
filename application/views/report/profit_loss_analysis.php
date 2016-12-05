<script type="text/javascript">
    function search_report_history() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getProfitLossHistory(startDate, endDate);
    }
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
        $('#payment_type').val('0');
    });
</script>
<div class="ezttle"><span class="text">Profit/Loss Analysis</span></div>
<div class="mypage" ng-controller="reportController" >
    <div class="top10">&nbsp;</div>
<!--    <ng-form>
        <ul class="list-unstyled paymentHistorySearch" ng-init="setServiceIdList('<?php echo htmlspecialchars(json_encode($service_list)) ?>')">
            <li>Start Date:</li>
            <li><input data-date-format='yyyy-mm-dd' id="start_date" type="text" size="18" placeholder="Start Date"  name="start_date" class="form-control input-xs customInputMargin"></li>
            <li>End Date:</li>
            <li><input data-date-format='yyyy-mm-dd' id="end_date" type="text" size="18" placeholder="End Date"  name="end_date" class="form-control input-xs customInputMargin"></li>

            <li>Service: </li>
            <li>
                <select  ng-model='searchInfo.serviceId' required ng-options='service.id as service.title for service in serviceList' class="form-control input-xs"></select>
            </li>
            <li>Show All:</li>
            <li> <input type="checkbox" ng-model="allTransactions"></li>
            <li><input id="search_submit_btn" ng-model="search" type="submit" size="18" value="Search" onclick="search_report_history()" class="button-custom"></li>
        </ul>
    </ng-form>-->
    <table class="table table-responsive green_color_table" ng-init="setReportList(<?php echo htmlspecialchars(json_encode($report_list)) ?>, <?php echo htmlspecialchars(json_encode($report_summary)) ?>)">
        This page will be updated later.
<!--        <thead>
            <tr>
                <th><a href="">Service</a></th>
                <th><a href="">TTL Request</a></th>
                <th><a href="">Successful</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Cost</a></th>
                <th><a href="">Profit</a></th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="reportInfo in reportList">
                <th>{{reportInfo.title}}</th>
                <th>{{reportInfo.total_request}}</th>
                <th>{{reportInfo.total_status_request}}</th>
                <th>{{reportInfo.total_amount}}</th>
                <th>{{reportInfo.total_used_amount}}</th>
                <th>{{reportInfo.total_profit}}</th>
            </tr>
        </tbody>        
        <tfoot>
            <tr>
                <td><a href="">Total</a></td>
                <td><a href="">{{reportSummery.total_request}}</a></td>
                <td><a href="">{{reportSummery.total_status_request}}</a></td>
                <td><a href="">{{reportSummery.total_amount}}</a></td>
                <td><a href="">{{reportSummery.total_used_amount}}</a></td>
                <td><a href="">{{reportSummery.total_profit}}</a></td>
            </tr>
        </tfoot>-->
    </table>
</div>



























<script type="text/javascript">
    function search_report_history() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getDetailRepotHistory(startDate, endDate);
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
<div class="ezttle"><span class="text">Detailed Report</span></div>
<div class="mypage" ng-controller="reportController">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled paymentHistorySearch">
            <li>Group:</li>
            <li ng-init="setSuccessorGroupList('<?php echo htmlspecialchars(json_encode($successor_group_list)) ?>')">
                <select  id="payment_type" ng-model="searchInfo.userGroupId" ng-change="getSuccessorListofGroup()" class="form-control input-xs customInputMargin">
                    <option  value="">Please select</option>
                    <option  ng-repeat="groupInfo in successorGroupList" value="{{groupInfo.id}}">{{groupInfo.title}}</option>
                </select>
            </li>
            <li>Reseller:</li>
            <li >
                <select  id="payment_type" ng-model="searchInfo.userId" class="form-control input-xs customInputMargin">
                    <option  value="">Please select</option>
                    <option id="get_child_list"  ng-repeat="resellerInfo in resellerList" value="{{resellerInfo.user_id}}">{{resellerInfo.username}}</option>
                </select>
            </li>
            <li>Start Date:</li>
            <li><input data-date-format='yyyy-mm-dd' id="start_date" type="text" size="18" placeholder="Start Date"  name="start_date" class="form-control input-xs customInputMargin"></li>
            <li>End Date:</li>
            <li><input data-date-format='yyyy-mm-dd' id="end_date" type="text" size="18" placeholder="End Date"  name="end_date" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" ng-model="search" type="submit" size="18" value="Search" onclick="search_report_history()" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive green_color_table" ng-init="setReportList(<?php echo htmlspecialchars(json_encode($report_list)) ?>, <?php echo htmlspecialchars(json_encode($report_summary)) ?>)">
        <thead>
            <tr>
                <th><a href="">Service</a></th>
                <th><a href="">Total Request</a></th>
                <th><a href="">Pending</a></th>
                <th><a href="">Processed</a></th>
                <th><a href="">Successful</a></th>                
                <th><a href="">Failed</a></th>
                <th><a href="">Cancelled</a></th>
                <th><a href="">% of Success</a></th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="reportInfo in reportList">
                <th>{{reportInfo.title}}</th>
                <th>{{reportInfo.total}}</th>
                <th>{{reportInfo.pending}}</th>
                <th>{{reportInfo.processed}}</th>
                <th>{{reportInfo.success}}</th>
                <th>{{reportInfo.failed}}</th>
                <th>{{reportInfo.cancelled}}</th>
                <th>{{reportInfo.ratio_success}}</th>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td><a href="">Total</a></td>
                <td>{{reportSummery.total_request}}</td>
                <td>{{reportSummery.total_pending}}</td>
                <td>{{reportSummery.total_processed}}</td>
                <td>{{reportSummery.total_success}}</td>                
                <td>{{reportSummery.total_failed}}</td>
                <td>{{reportSummery.total_cancelled}}</td>
                <td>{{reportSummery.total_ratio_success}}</td>
            </tr>
        </tfoot>
    </table>

</div>



























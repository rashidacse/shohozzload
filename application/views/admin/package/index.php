<script>
    function delete_package(packageInfo) {
        angular.element($('#delete_package')).scope().setPackageId(packageInfo.package_id);
        $("#confirmation_content").html("Do you want to delete this package?");
        $('#common_confirmation_modal').modal('show');
        $('#modal_confirm_click_id').on("click", function() {
            angular.element($('#delete_package')).scope().deletePackage(function(data) {
                $("#content").html(data.message);
                $('#common_modal').modal('show');
                $('#modal_ok_click_id').on("click", function() {
                    window.location = '<?php echo base_url() ?>package/';
                });
            });
        });
        $('#modal_cancel_click_id').on("click", function() {
             window.location = '<?php echo base_url() ?>package/';
        });
    } 
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Package</span></div>
<div class="mypage" ng-app="app.Package" ng-controller="packageController">
    <div class="row form-group" ng-init="setPackageList('<?php echo htmlspecialchars(json_encode($package_list)) ?>')">
        <div class="col-md-12">
            <div class="btn-group">
                <a href="<?php echo base_url(); ?>package/create_package" class="button-custom"><span class="glyphicon glyphicon-plus-sign"></span> Create Package</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="help-block">Package List</p>
            <div style="margin:0px;padding:0px;background:#fff;">
                <table class="table10" cellspacing="0">
                    <thead>
                        <tr>	
                            <th>Operator</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="packageInfo in packageList">
                            <td>{{packageInfo.operator_title}}</td>
                            <td>{{packageInfo.package_title}}</td>
                            <td>{{packageInfo.amount}}</td>
                            <td>
                                <a href="<?php echo base_url() . "package/update_package/" ; ?>{{packageInfo.package_id}}">
                                    Edit
                                </a>
                            </td> 
                            <td><a id="delete_package" onclick="delete_package(angular.element(this).scope().packageInfo)">delete</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="other-controller">
                <div class="text-center">
                    <dir-pagination-controls boundary-links="true" on-page-change="" template-url="<?php echo base_url(); ?>history/pagination_tmpl_load"></dir-pagination-controls>
                </div>
            </div>
        </div>
    </div>
</div>
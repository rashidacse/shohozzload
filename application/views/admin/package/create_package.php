<script>
    function create_package(packageInfo) {
        if (typeof packageInfo.operatorId == "undefined" || packageInfo.operatorId.length == 0 || packageInfo.operatorId == 0) {
            $("#content").html("Please select an operator first.");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof packageInfo.title == "undefined" || packageInfo.title.length == 0) {
            $("#content").html("Please assign package title.");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof packageInfo.amount == "undefined" || packageInfo.amount.length == 0) {
            $("#content").html("Please assign package amount.");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($("#submit_create_package")).scope().createPackage(function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>package';
            });
        });
    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Create Package</span></div>
<div class="mypage" ng-app="app.Package" ng-controller="packageController">
    <div class="row" ng-init="setOperatorList('<?php echo htmlspecialchars(json_encode($operator_list)) ?>')">
        <div class="col-md-6">
            <div class="row form-group">
                <div class="col-md-5">	
                    <label for="type" class="col-md-6 control-label requiredField label_custom">
                        Operator
                    </label>
                </div>
                <div class="col-md-7">	
                    <select  for="type" id="type"  ng-model="packageInfo.operatorId" class="form-control control-label requiredField">
                        <option ng-selected="operatorInfo.selected" class=form-control ng-repeat="operatorInfo in operatorList" value="{{operatorInfo.operator_id}}">{{operatorInfo.title}}</option>
                    </select>
                </div> 
            </div>
            <div class="row form-group">
                <div class="col-md-5">	
                    <label for="type" class="col-md-6 control-label requiredField label_custom">
                        Package
                    </label>
                </div>
                <div class="col-md-7">	
                    <label for="number" class="control-label requiredField label_custom">
                        <input type="text" name="" id=""  class="form-control" placeholder="eg: 1GB Package" ng-model="packageInfo.title"> 
                    </label>
                </div> 
            </div>
            <div class="row form-group">
                <div class="col-md-5">	
                    <label for="type" class="col-md-6 control-label requiredField label_custom">
                        Amount
                    </label>
                </div>
                <div class="col-md-7">	
                    <label for="number" class="control-label requiredField label_custom">
                        <input type="text" name="" id=""  class="form-control" placeholder="eg: 100" ng-model="packageInfo.amount"> 
                    </label>
                </div> 
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <button id="submit_create_package" name="submit_create_package" class="button-custom pull-right" onclick="create_package(angular.element(this).scope().packageInfo)">Create</button>
                </div> 
            </div> 
        </div>
    </div>
</div>
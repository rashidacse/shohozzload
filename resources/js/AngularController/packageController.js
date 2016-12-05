angular.module('controller.Package', ['services.Package']).
        controller('packageController', function ($scope, packageService) {
            //package management
            $scope.packageInfo = {};
            $scope.packageId = 0;
            $scope.operatorList = [];
            $scope.packageList = [];
            
            //package transaction
            $scope.topUpInfo = {};
            $scope.topupTypeList = [];
            $scope.transctionList = [];
            
            
            $scope.allow_package_action = true;
            $scope.setPackageList = function (packageList) {
                $scope.packageList = JSON.parse(packageList);
            };
            $scope.setOperatorList = function (operatorList) {
                $scope.operatorList = JSON.parse(operatorList);
            };
            $scope.createPackage = function (callbackFunction) {
                if ($scope.allow_package_action == false) {
                    return;
                }
                $scope.allow_package_action = false;
                packageService.createPackage($scope.packageInfo).
                    success(function (data, status, headers, config) {
                        $scope.allow_package_action = true;
                        callbackFunction(data);
                    });
            };
            $scope.initUpdatePackage = function (packageInfo, operatorList) {
                $scope.packageInfo = JSON.parse(packageInfo);
                $scope.operatorList = JSON.parse(operatorList);
            };
            $scope.updatePackage = function (callbackFunction) {
                if ($scope.allow_package_action == false) {
                    return;
                }
                $scope.allow_package_action = false;
                packageService.updatePackage($scope.packageInfo).
                    success(function (data, status, headers, config) {
                        $scope.allow_package_action = true;
                        callbackFunction(data);
                    });
            };
            $scope.setPackageId = function (packageId) {
                $scope.packageId = packageId;
            };
            $scope.deletePackage = function (callbackFunction) {
                if ($scope.allow_package_action == false) {
                    return;
                }
                $scope.allow_package_action = false;
                packageService.deletePackage($scope.packageId).
                    success(function (data, status, headers, config) {
                        $scope.allow_package_action = true;
                        callbackFunction(data);
                    });
            };
            $scope.initPackageRecharge = function (transctionList, operatorList, topupTypeList) {
                $scope.transctionList = JSON.parse(transctionList);   
                $scope.operatorList = JSON.parse(operatorList);
                $scope.topupTypeList = JSON.parse(topupTypeList);
                $scope.topupTypes = {
                    topupTypeList: $scope.topupTypeList,
                    selectedOption: $scope.topupTypeList[0]
                };
            };
            $scope.getOperatorPackages = function (topupOperatorId) {
                $scope.topUpInfo.topupOperatorId = topupOperatorId;
                if ($scope.allow_package_action == false) {
                    return;
                }
                $scope.allow_package_action = false;
                packageService.getOperatorPackages($scope.topUpInfo.topupOperatorId).
                    success(function (data, status, headers, config) {
                        $scope.allow_package_action = true; 
                        $scope.packageList = data.package_list; 
                    });
            };
            $scope.getPackageInfo = function (packageId) {
                $scope.topUpInfo.packageId = packageId;
                if ($scope.allow_package_action == false) {
                    return;
                }
                $scope.allow_package_action = false;
                packageService.getPackageInfo($scope.topUpInfo.packageId).
                    success(function (data, status, headers, config) {
                        $scope.allow_package_action = true; 
                        $scope.topUpInfo.amount = data.package_info.amount;
                    });
            };
            $scope.packageRecharge = function (callbackFunction) {
                if ($scope.allow_package_action == false) {
                    return;
                }
                $scope.allow_package_action = false;
                var tempArray = [];
                tempArray.push($scope.topUpInfo);
                packageService.packageRecharge(tempArray).
                    success(function (data, status, headers, config) {
                        $scope.allow_package_action = true;
                        callbackFunction(data);
                    });
            };
            $scope.getAjaxTransactionList = function (serviceIdList) {
                packageService.getAjaxTransactionList(serviceIdList).
                        success(function (data, status, headers, config) {
                            $scope.transctionList = data.transaction_list;
                        });
            };
        });



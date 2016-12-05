angular.module('services.Package', []).
    factory('packageService', function ($http, $location) {
        var $app_name = "/rechargeserver/";
        var packageService = {};
        packageService.createPackage = function (packageInfo) {
            return $http({
                method: 'post',
                url: $location.path() + $app_name + 'package/create_package',
                data: {
                    packageInfo : packageInfo
                }
            });
        };
        packageService.updatePackage = function (packageInfo) {
            return $http({
                method: 'post',
                url: $location.path() + $app_name + 'package/update_package',
                data: {
                    packageInfo : packageInfo
                }
            });
        };
        packageService.deletePackage = function (packageId) {
            return $http({
                method: 'post',
                url: $location.path() + $app_name + 'package/delete_package',
                data: {
                    packageId : packageId
                }
            });
        };
        
        //package recharge module
        packageService.getOperatorPackages = function (operatorId) {
            return $http({
                method: 'post',
                url: $location.path() + $app_name + 'package_recharge/get_operator_packages',
                data: {
                    operatorId : operatorId
                }
            });
        };
        packageService.getPackageInfo = function (packageId) {
            return $http({
                method: 'post',
                url: $location.path() + $app_name + 'package_recharge/get_package_info',
                data: {
                    packageId : packageId
                }
            });
        };
        packageService.packageRecharge = function (transactionDataList) {
            return $http({
                method: 'post',
                url: $location.path() + $app_name + 'package_recharge',
                data: {
                    transactionDataList: transactionDataList
                }
            });
        };
        packageService.getAjaxTransactionList = function(serviceIdList) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + 'transaction/get_transaction_list',
                    data: {
                        serviceIdList: serviceIdList
                    }
                });
            };
        return packageService;
    });


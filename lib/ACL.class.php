<?php

class ACL{
    public static $controllers = array(
        "IndexNew",
        "BuyerRank",
        "StockBook",
        "Admin",
        "Buyer",
        "OwnBuyer",
        "Live",
//        "LiveFlow",
        "LiveStock",
        "Stock",
        "Order",
        "OwnOrder",
        "Payment",
        "Pack",
        "Storage",
        "StoragePurchasePending",
        "StorageCsPending",
        "Logistic",
        "ExpressPrint",
        "UserRefund",
        "DeliveryAbroad",
        "User",
        "BuyerWithdraw",
        "Coupon",
        "UserReminder",
        "TaskPush",
        "SystemLog",
        "OrderLog",
        "EasemobMsg",
        "Cs",
        "Permission",
        "Group",
        "AdminGroup",
        "RolePermission",
        "Action",
        "Index",
        "StockAmount",
    );

    public static function checkPermission($permissionName){
        if(!ACL::checkPermissionResult($permissionName)){
            //throw new ModelAndViewException("no permission", 1, "json:",AppUtils::returnValue(['msg'=>'no permission'], '90001'));
            throw new ModelAndViewException("no permission", 1, ROOT_PATH.'/template/no_permission.tpl');}
    }

    public static function checkPermissionResult($actionName){
        $admin_id = Admin::getCurrentAdmin()->mId;
        if(!$admin_id)  return false;

        # 用户名为root直接获取权限
        $admin_name = Admin::getCurrentAdmin()->mName;
        if($admin_name == 'root'){
            return true;
        }

        # 非root才走权限验证流程
        #$permissionName = Action::getAuth($actionName);
        #if(!$permissionName) return false;
        $permissionId = Action::getPermissionId($actionName);
        if(!$permissionId) return false;

        if(!Permission::exist($permissionId))   return false;

        # 首先直接判断用户直接权限
        $permission_ids = RolePermission::getPermissionIdsByAdmin($admin_id);
        #if(Permission::checkPermission($permission_ids, $permissionName)){
        #    return true;
        #}
        if(in_array($permissionId, $permission_ids))    return true;

        # 用户没有直接权限则查看用户的组权限
        $group_ids = AdminGroup::getGroupIdsByAdmin($admin_id);
        if(count($group_ids) == 0)  return false;
        if(Group::isRoot($group_ids)){
            return true;
        }
        $permission_ids = RolePermission::getPermissionIdsByGroup($group_ids);
        #return Permission::checkPermission($permission_ids, $permissionName);
        if(in_array($permissionId, $permission_ids))    return true;
        return false;
    } 

    public static function getPermissionControllers(){
        $permissionControllers = array();

        $admin_id = Admin::getCurrentAdmin()->mId;
        if(!$admin_id)  return $permissionControllers;

        $admin_name = Admin::getCurrentAdmin()->mName;
        if($admin_name == 'root'){
            return self::$controllers;
        }
        
        $group_ids = AdminGroup::getGroupIdsByAdmin($admin_id);
        $permission_ids_by_group = array();
        if(count($group_ids) > 0){
            if(Group::isRoot($group_ids)){
                return self::$controllers;
            }
            $permission_ids_by_group = RolePermission::getPermissionIdsByGroup($group_ids);
        }

        # 根据用户权限和组权限找到用户的所有权限
        $permission_ids_by_admin = RolePermission::getPermissionIdsByAdmin($admin_id);
        $permission_ids = array_unique(array_merge($permission_ids_by_admin, $permission_ids_by_group));

        if(count($permission_ids) == 0) return $permissionControllers;
        #$permissionNames = Permission::getPermissionNames($permission_ids); 
        #$actionNames = Action::getActionNames($permissionNames);
        $actionNames = Action::getActionNames($permission_ids);
    
        $myControllers = array();
        if(count($actionNames) > 0){
            foreach(self::$controllers as $c){
                $controllerIndex = strtolower($c).'_index';
                if(in_array($controllerIndex,$actionNames)){
                    array_push($myControllers, $c);
                }
            }
        }
        return $myControllers;
    }
}

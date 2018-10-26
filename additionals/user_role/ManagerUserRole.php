<?php
namespace additionals\user_role;
use entities\ActionsOnModules;
use lib\sql\SqlQuery;

/**
 * Manager of User Role
 *@author PhpGenerator (https://github.com/leplutonien/php_generator)
 */

class ManagerUserRole{
    private static $managerUserAccess;
    /**
     * Information about the current user
     */
    private $currentUser;
    /**
     * All accesses of the current user
     */
    private $access;

    public function __construct(\entities\Users $currentUser){
        $this->currentUser = $currentUser;

        //get the accesses defined for all roles
        $rows = null;
        $sql = ' select distinct am.id_m , am.id_a from actions_on_modules am, roles_access ra, users_roles ur, users u '.
            'where u.id_user = ur.id_user and ur.id_r = ra.id_r and ra.id_am = am.id_am and u.id_user = :id_user ';

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setParams('id_user',$this->currentUser->getIdUser());
        $resultSet = $sqlQuery->execute();
        $resultSet->getAllRows();

        while ($r = $resultSet->nextRow())
            $rows [] = new ActionsOnModules($r);

        $this->access = $rows;

        self::$managerUserAccess = $this;

    }

    /**
     *Allows to know if the user has a given action on a module
     */
    public function hasAccess($module, $action){
        if(count($this->access) > 0){
            foreach($this->access as $a){
                if($a->getIdM() == $module && $a->getIdA() == $action)
                    return true;
            }
        }
        return false;
    }

    /**
     * Allows to know if the user can access modules
     */
    public function haveAccessOneofModules(array $module){
        if(count($this->access) > 0 && count($module) > 0 ){
            foreach($this->access as $a){
                foreach($module as $m){
                    if($a->getIdM() == $m)
                        return true;
                }
            }
        }
        return false;
    }

    /**
     * Allows to know if the user has rights on a module
     */
    public function canExcuteOneofActions($module, array $actions){
        if(count($this->access) > 0 && count($actions) > 0 ){
            foreach($this->access as $d){
                foreach($actions as $a){
                    if($d->getIdA() == $a && $d->getIdM() == $module)
                        return true;
                }
            }
        }
        return false;
    }

    /**
     * @return get Information about the current user
     */
    public function getCurrentUser(){
        return $this->currentUser;
    }

    /**
     * @return ManagerUserRole
     */
    public static function getManagerUserAccess(){
        return self::$managerUserAccess;
    }
}
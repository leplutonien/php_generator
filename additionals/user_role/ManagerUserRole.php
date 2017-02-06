<?php
namespace additionals\user_role;
use entities\ActionsOnModules;
use entities\Roles;
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
     * role of current user
     */
    private $currentUserRole;
    /**
     * All accesses of the current user
     */
    private $access;

    public function __construct(\entities\User $currentUser){
        $this->currentUser = $currentUser;
        $role = Roles::findByAttribute(array(
                'id_r' => $currentUser->getIdR()
            )
        )[0];

        if(!is_null($role)){
            $this->currentUserRole = $role;
            //get the accesses defined for the role
            $rows = null;
            $sql = 'SELECT am.id_am, am.id_m, am.id_a from actions_on_modules am, have_roles hr '.
                'WHERE am.id_am = hr.id_am AND hr.id_r= :role';

            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setParams('role',$role->getIdR());
            $resultSet = $sqlQuery->execute();
            $resultSet->getAllRows();

            while ($r = $resultSet->nextRow())
                $rows [] = new ActionsOnModules($r);

            $this->access = $rows;

            self::$managerUserAccess = $this;
        }else{
            throw new \InvalidArgumentException('No User role has defined. ');
        }
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
     * @return get role of current user
     */
    public function getCurrentUserRole(){
        return $this->currentUserRole;
    }

    /**
     * @return ManagerUserRole
     */
    public static function getManagerUserAccess(){
        return self::$managerUserAccess;
    }
}
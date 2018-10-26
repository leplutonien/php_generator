<?php
namespace additionals\session_token;
use entities\Sessions;
use lib\BoxFunctions;
use lib\sql\SqlQuery;

/**
 * Manage session token by using Json Web Token
 *@author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class SessionToken{
    private static $sessionToken;
    private $secret_server_key = "neinotulpeBJl1960";
    private $token_validity = 60 * 30 ; //30min
    private $access_code_validity = 60 * 1440 ; //24h
    private $pwd_reset_token_validity = 60 * 1440 ; //24h
    private $current_token = null;
    private $current_id_session = null;

    public function __construct($token = null){
        $this->current_token = $token;
        self::$sessionToken = $this;
    }

    public function setIdSession($id){
        $this->current_id_session = $id;
    }

    public static function getSessionToken(){
        return self::$sessionToken;
    }

    /**
     * create new instance of session
     */
    public function create($user){
        $this->clean();

        $csession = Sessions::findByAttribute(array(
            "id_user"=> $user
        ));

        if( empty($csession) || count($csession) == 0 ){
            $nsession = new Sessions(
                array(
                    "id" => (int)BoxFunctions::AutomaticNumber('sessions','id',null,5),
                    "id_user"=> $user,
                    "timestamp"=> time()
                )
            );
            $nsession->insert();
            $this->current_id_session =  $nsession->getId();
            return $nsession->getId();
        }else{
            $this->current_id_session = $csession[0]->getId();
            return $csession[0]->getId();
        }
    }

    public function setToken($data){
        $token = JWT::encode($data,$this->secret_server_key);
        $this->clean();

        $csession = Sessions::findByAttribute(array(
            "id"=> $this->current_id_session
        ));

        if(!is_null($csession) && count($csession) == 1){
            $s =  $csession[0];
            $s->setToken($token);
            $s->setTimestamp(time());
            $s->update();
            $this->current_token = $token;
            return $token;
        }else{
            return null;
        }
    }

    /**
     * set access control code
     */
    public function setAccessControlCode($code){
        $this->clean();
        $csession = Sessions::findByAttribute(array(
            "id"=> $this->current_id_session
        ));

        if( empty($csession) || count($csession) == 0 )
            return false;
        else {
            $s =  $csession[0];
            $s->setAccessControlCode($code);
            $s->setTimestamp(time());
            return $s->update();
        }
    }

    /**
     * set pwd reset token
     */
    public function setPwdResetToken($reset_token){
        $this->clean();
        $csession = Sessions::findByAttribute(array(
            "id"=> $this->current_id_session
        ));

        if( empty($csession) || count($csession) == 0 )
            return false;
        else {
            $s =  $csession[0];
            $s->setPwdResetToken($reset_token);
            $s->setTimestamp(time());
            return $s->update();
        }
    }

    public function sessionInfo(){
        $this->clean();
        return JWT::decode($this->current_token,
            $this->secret_server_key);
    }

    public function updateTime(){
        $this->clean();
        $csession = Sessions::findByAttribute(array(
            "token"=> $this->current_token
        ));

        if( empty($csession) || count($csession) == 0 )
            return false;
        else {
            $s =  $csession[0];
            $s->setTimestamp(time());
            return $s->update();
        }
    }

    /**
     * remove session via specific token
     */
    public function remove(){
        $this->clean();

        $csession = Sessions::findByAttribute(array(
            "token"=> $this->current_token
        ));

        if( !empty($csession) && count($csession) == 1 )
            return $csession[0]->remove();
        else
            return false;
    }

    /**
     *Deletes all sessions whose time variable is less than
     * and update token
     * @return bool
     * @throws \Exception
     */
    public function clean(){
        //delete instances
        $sql = " DELETE FROM sessions WHERE timestamp < :time";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setParams('time',time() - $this->token_validity);
        $resultSet = $sqlQuery->execute();

        //update access_control_code
        $sql = " UPDATE sessions set access_control_code = null WHERE timestamp < :time";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setParams('time',time() - $this->access_code_validity);
        $resultSet = $sqlQuery->execute();

        //update pwd_reset_token
        $sql = " UPDATE sessions set pwd_reset_token = null WHERE timestamp < :time";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setParams('time',time() - $this->pwd_reset_token_validity);
        $resultSet = $sqlQuery->execute();
    }
}
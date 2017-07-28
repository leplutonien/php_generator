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
    private static $token_storage = "/additionals/sessions.json";
    private  static  $secret_server_key = "neinotulpeBJl1960";
    private $token ;
    private static  $validity = 60 * 2 ; //15min


    public static  function create($token){
        $token = JWT::encode($token,SessionToken::$secret_server_key);
        SessionToken::clean();

        $csession = Sessions::findByAttribute(array(
            "token"=> $token
        ));

        if( empty($csession) || count($csession) == 0 ){
            $nsession = new Sessions(
                array(
                    "id" => (int)BoxFunctions::AutomaticNumber('sessions','id',null,5),
                    "token"=> $token,
                    "timestamp"=> time()
                )
            );
            $nsession->insert();
            return $token;
        }else{
            return $csession[0]->getToken();
        }
    }

    public static function sessionInfo($token){
        SessionToken::clean();

        $csession = Sessions::findByAttribute(array(
            "token"=> $token
        ));

        if( empty($csession) || count($csession) == 0 )
            return null;
        else {
           $s =  $csession[0];
            $s->setTimestamp(time());
            $s->update();
            return JWT::decode($token, SessionToken::$secret_server_key);
        }
    }

    public static function remove($token){
        SessionToken::clean();

        $csession = Sessions::findByAttribute(array(
            "token"=> $token
        ));

        if( !empty($csession) && count($csession) == 1 )
            return $csession[0]->remove();
        else
            return false;
    }

    /**
     *
    Deletes all sessions whose time variable is less than
     * @return bool
     * @throws \Exception
     */
    public static function clean(){
        $sql = " DELETE FROM sessions WHERE timestamp < :time";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setParams('time',time() - SessionToken::$validity);
        $resultSet = $sqlQuery->execute();
        return $resultSet->getStatus();
    }
}
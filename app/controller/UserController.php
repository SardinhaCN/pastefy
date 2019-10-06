<?php
namespace app\controller;

use \ulole\core\classes\Response;

class UserController {

    public static function login() {
        echo \json_encode(\app\classes\User::getUserInformation($_GET["userkey"]));
        if (isset($_GET["userkey"])) {
            if (\app\classes\User::getUserInformation($_GET["userkey"]) !== false) {
                $key = \app\classes\User::getUserInformation($_GET["userkey"])->userkey;
                $newUser = new \app\classes\User($key);
                $newUser->login();
                \setcookie("InteraApps_auth", $newUser->session, time()+1593600, "/");
                
                Response::redirect('/');
            }
        }        
    }

}

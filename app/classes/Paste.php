<?php
namespace app\classes;

use app\classes\User;
use \databases\PasteTable;
use \ulole\core\classes\Response;
use \ulole\core\classes\util\Str;
use \ulole\core\classes\util\secure\AES;
use \ulole\core\classes\util\secure\Hash;
use \ulole\core\classes\util\cookies\Cookies;
use \ulole\core\classes\util\cookies\CookieBuilder;

class Paste {

    public static function getPaste($paste, $password=null) {
        $pasteTable = new PasteTable;
        $content = $pasteTable->select('id, content, title, created')->where("id",$paste)->first();
        
        if ($password!=null)
            $password = $content["id"].$password;
        else
            $password = $content["id"];
        
            if (Cookies::isset("\$_pastepw_".$paste)) 
            $password = $content["id"].Cookies::get("\$_pastepw_".$paste);
        if ($content != null)
            return [
                "exists"=>true,
                "id"=>$content["id"],
                "title"=>AES::decrypt($content["title"], $content["id"]),
                "created"=>$content["created"],
                "content"=>AES::decrypt($content["content"], $password)
            ];
        return ["exists"=>false];
    }

}
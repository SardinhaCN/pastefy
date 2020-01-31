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

    private $folder;
    private $title;
    private $content;
    private $password = null;
    private $user = null;

    public function save() : string {
        $id = (function() {
            $id = Str::random(8);
            while( count((new PasteTable)->select('id')->where("id",$id)->get()) > 0) {
                $id = Str::random(8);
            }
            return $id;
        })();
        $pasteTable = new PasteTable;
        $pasteTable->id = $id;

        if (isset($this->folder) && ((\app\classes\User::usingIaAuth()) ? \app\classes\User::loggedIn() : false)) {
            $folder = (new \databases\PasteFolderTable)->select("id")
                    ->where("userid", \app\classes\User::getUserObject()->id)
                    ->andwhere("id", $this->folder);
            if (count($folder->run()) > 0)
                $pasteTable->folder = $folder->first()["id"];
        }

        $pasteTable->title =  AES::encrypt($this->title, $id);
        if (isset($this->password) && $this->password != "")
            $pasteTable->password = Hash::sha512($this->password);

        $pasteTable->content = AES::encrypt($this->content, $id.((isset($this->password)) ? $this->password : ""));
        $pasteTable->created = date("Y-m-d H:i:s");
        $pasteTable->encrypted = "1";
        if ($this->user !== null){
            $pasteTable->userid = $this->user;
        }
        $pasteTable->save();
        return $id;
    }

    
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


    public function getFolder() {
        return $this->folder;
    }

    public function setFolder($folder) {
        $this->folder = $folder;
        return $this;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    public function getContent(){
        return $this->content;
    }

    public function setContent($content){
        $this->content = $content;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    public function getUser(){
        return $this->user;
    }

    public function setUser($user){
        $this->user = $user;

        return $this;
    }
}
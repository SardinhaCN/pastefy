<?php
namespace app\controller;

use app\classes\Paste;
use app\classes\User;
use \databases\PasteTable;
use \ulole\core\classes\Response;
use \ulole\core\classes\util\Str;
use \ulole\core\classes\util\secure\AES;
use \ulole\core\classes\util\secure\Hash;
use \ulole\core\classes\util\cookies\Cookies;
use \ulole\core\classes\util\cookies\CookieBuilder;

class PasteController {

    public static function openPaste() {
        global $_ROUTEVAR, $_GET;

        $pasteTable = new PasteTable;
        if (count(($pasteTable)->select('*')->where("id",$_ROUTEVAR[1])->get()) > 0) {
            $content = $pasteTable->select('*')->where("id",$_ROUTEVAR[1])->first();
            $password = $content["id"];
            if (Cookies::isset("\$_pastepw_".$_ROUTEVAR[1]))
                $password = $content["id"].Cookies::get("\$_pastepw_".$_ROUTEVAR[1]);
            if (User::loggedIn())
                $user = User::getUserObject();
            \view("paste", [
                "id"=>$content["id"],
                "pastetitle"=>\htmlspecialchars(AES::decrypt($content["title"], $content["id"])),
                "code"=>\htmlspecialchars(AES::decrypt($content["content"], $password)),
                "mypaste"=>((new \databases\PasteTable)->select("userid")->where("id", $_ROUTEVAR[1])->first()["userid"] == (User::loggedIn() ? $user->id : "NOPE" ) ),
                "needspassword"=>($content["password"] != "" && !Cookies::isset("\$_pastepw_".$_ROUTEVAR[1]))
            ]);
        } else
            \view("404");
    }

    public static function password() {
        global $_ROUTEVAR;
        $pasteTable = new PasteTable;
        if (count($pasteTable->select('*')->where("id",$_ROUTEVAR[1])->get()) > 0) {
            $content = $pasteTable->select('*')->where("id",$_ROUTEVAR[1])->first();
            if ($content["password"] == Hash::sha512($_POST["password"])) {
                // Yes... passwords will be saved as cookie. The reason is, that we don't want to save encryption-keys on servers.
                (new CookieBuilder(
                    "\$_pastepw_".$_ROUTEVAR[1], 
                    $_POST["password"]
                ))->time(CookieBuilder::WEEK*4)->path("/")->build();
                Response::redirect("/".$_ROUTEVAR[1]);
            } else \view("error", ["message"=>"Password is invalid!"]);
        } else \view("404");

    }

    public static function rawPaste() {
        global $_ROUTEVAR, $_GET;

        Response::setContentType("text/plain");
        $password = null;
        if (isset($_GET["password"])) 
            $password = $_GET["password"];
        
        $pasteContents = Paste::getPaste($_ROUTEVAR[1], $password);

        if ($pasteContents["exists"])
            return $pasteContents["content"];
        else return "Not found";
    }

    public static function createPaste() {
        global $_POST;
        if ($_POST["content"] != "") {
            $id = (function() {
                $id = Str::random(8);
                while( count((new PasteTable)->select('id')->where("id",$id)->get()) > 0) {
                    $id = Str::random(8);
                }
                return $id;
            })();
            $pasteTable = new PasteTable;
            $pasteTable->id = $id;

            if (isset($_POST["folder"]) && ((\app\classes\User::usingIaAuth()) ? \app\classes\User::loggedIn() : false)) {
                $folder = (new \databases\PasteFolderTable)->select("id")
                        ->where("userid", \app\classes\User::getUserObject()->id)
                        ->andwhere("id", $_POST["folder"]);
                if (count($folder->run()) > 0)
                    $pasteTable->folder = $folder->first()["id"];
            }

            $pasteTable->title =  AES::encrypt($_POST["title"], $id);
            if (isset($_POST["password"]) && $_POST["password"] != "")
                $pasteTable->password = Hash::sha512($_POST["password"]);

            $pasteTable->content = AES::encrypt($_POST["content"], $id.((isset($_POST["password"])) ? $_POST["password"] : ""));
            $pasteTable->created = date("Y-m-d H:i:s");
            $pasteTable->encrypted = "1";
            if ( (\app\classes\User::usingIaAuth()) ? \app\classes\User::loggedIn() : false ){
                $pasteTable->userid = User::getUserObject()->id;
            }
            $pasteTable->save();
            Response::redirect("/".$id);
        } else \view("error", ["message"=>"Please insert content!"]);
    }

    public static function pasteList() {
        global $ULOLE_CONFIG_ENV;
        if (User::usingIaAuth()) {
            $user = User::getUserObject();
            if (!$user || !$user->valid) {
                header("Location: ".$ULOLE_CONFIG_ENV->Auth->returnurl);
                die("");
            }

            $folder = (new \databases\PasteFolderTable)->select("*")->where("userid", $user->id)->andwhere("parent", "")->order("created DESC")->get();
            $pastes = (new PasteTable)->select("*")->where("userid", $user->id)->andwhere("folder","")->order("created DESC")->get();


            \view("pastelist", [
                "folder"=>$folder,
                "pastes"=>$pastes
            ]);

        } else 
            \view("404");
    }

}

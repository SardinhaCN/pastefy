<?php
namespace app\controller\api\v1;

use app\classes\Paste;
use \ulole\core\classes\Response;

class PasteController {

    public static function get() {
        global $_ROUTEVAR, $_GET;

        $password = null;
        if (isset($_GET["password"])) 
            $password = $_GET["password"];
        
        $pasteContents = Paste::getPaste($_ROUTEVAR[1], $password);

        if ($pasteContents["exists"])
            Response::json($pasteContents);
        else Response::json(["done"=>false]);
    }

}
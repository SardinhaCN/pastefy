<?php
namespace app\controller\language;

use \ulole\core\classes\Response;

class MarkdownLanguageController {

    public static function markdown() {
        $out = [
            "done"=>false
        ];
        
        if (isset($_POST["markdown"])) {
            $parse = (new \modules\parsedown\Parsedown);
            $parse->setSafeMode(true);

            $markdown = $parse->text($_POST["markdown"]);
            $out["done"] = true;
            $out["out"] = $markdown;
        }

        Response::json($out);
    }

    
}
<?php
/*

"/"          =   Homepage
"@__404__@"  =   Page not found

(Do not use duplicated keys!)

You can use also this syntax for adding pages
$router->get("/test1", "homepage");
*/

// Directory for the views
$views_dir      =  "resources/views/";
$templates_dir  =  "resources/views/templates/";

$route = [
  "@__404__@"                =>     "404.php"
];

$router->get("/", "homepage.php");

$router->get("/folder/", "404.php");
$router->get("/pasteList", "!PasteController@pasteList");

$router->get("/user/login", "!UserController@login");

$router->post("/create:folder", "!FolderController@createFolder");
$router->post("/create:paste", "!PasteController@createPaste");

$router->post("/get:folder", "!FolderController@getMyDirectories");

$router->get("/([a-zA-Z0-9]*)", "!PasteController@openPaste");
$router->get("/p/([a-zA-Z0-9]*)", "!PasteController@openPaste");

$router->get("/folder/([a-zA-Z0-9]*)", "!FolderController@folder");

$router->get("/p/login/([a-zA-Z0-9]*)", "!PasteController@password");
$router->get("/p/raw/([a-zA-Z0-9]*)", "!PasteController@rawPaste");
$router->get("/raw/([a-zA-Z0-9]*)", "!PasteController@rawPaste");
$router->get("/([a-zA-Z0-9]*)/raw", "!PasteController@rawPaste");

$router->get("/delete:folder/([a-zA-Z0-9]*)", "!DeleteController@deleteFolder");
$router->get("/delete:paste/([a-zA-Z0-9]*)", "!DeleteController@deletePaste");

/* API */

$router->get("/dev/console", "!api\developers\DeveloperConsoleController@page");
$router->get("/dev/console/ls", "!api\developers\DeveloperConsoleController@list");
$router->post("/dev/console/newkey", "!api\developers\DeveloperConsoleController@createNewKey");
$router->post("/dev/console/deletekey", "!api\developers\DeveloperConsoleController@deleteKey");

$router->get("/api/v1/embed/([a-zA-Z0-9]*)", "!api\\v1\\EmbedController@embed");

$router->get("/api/v1/get/([a-zA-Z0-9]*)", "!api\\v1\PasteController@get");

//Docs
$router->get("/docs/v1/(.*)", "!docs\DocsV1Controller@page");
$router->get("/docs/v1", function() {
    $page = (new \modules\parsedown\Parsedown)->text(file_get_contents(\app\controller\docs\DocsV1Controller::PAGES[""]));
    return view("docs/v1", ["doc"=>$page, "pages"=>\app\controller\docs\DocsV1Controller::PAGES_LINKS]); });


// Language features
$router->post("/api/v1/language/markdown", "!language\MarkdownLanguageController@markdown");


$router->set($route);
<?php



echo "\n-- ULOLE-TESTING SERVER! --\n\n".$done_prefix."listening to \033[34mlocalhost:8000\033[0m\n";

if (!file_exists("env.json")) 
    echo $warn_prefix." env.json not found!\n";

if (!file_exists("conf.json")) 
    echo $warn_prefix." conf.json not found!\n";

file_put_contents("public/testserver.php",
'<?php if (file_exists("." . $_SERVER[\'REQUEST_URI\'])) return false; ?>'.file_get_contents("public/index.php"));

$exec= 'cd public
php -S 0.0.0.0:8000 -t ./ testserver.php';
system($exec);
exec($exec);
shell_exec($exec);

// This will actually just happens if the system() function crashes
echo $error_prefix."The server couldn't start!
Type this to run the server otherwise:
cd public
php -S 0.0.0.0:8000 -t ./ testserver.php
";


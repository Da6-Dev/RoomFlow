<?php
    session_start();

    
    require_once __DIR__.'/core/core.php';
    require_once __DIR__.'/router/routes.php';
    require_once __DIR__.'/validators/Validators.php';
    
    spl_autoload_register(function($file){
        if (file_exists(__DIR__."/utils/$file.php")){
            require_once __DIR__."/utils/$file.php";
        } elseif (file_exists(__DIR__."/models/$file.php")) {
            require_once __DIR__."/models/$file.php";
        } elseif (file_exists(__DIR__."/validators/$file.php")){
            require_once __DIR__."/validators/$file.php";
        }
    });
    
    $core = new core();
    $core-> run($Routes);
?>

    
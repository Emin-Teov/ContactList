<?php
    define("EXEC", true);
    define("SERVER", "ContactList");
    define("HEADER", "Contacts List");
    session_start();
    include __DIR__.'/src/Factor.php';
    include __DIR__.'/resources/views/header.php'; 
    echo md5("PASS:qwerty")."<br>";
    echo md5("PASS:asdfgh")."<br>";
    $sort_array = array('id'=>"By created date",'name'=>"By name", 'surname'=>"By surname", 'email'=>"By mail", 'phone'=>"By phone");
    $contactData = null;
    $desk = 'id';
    if(isset($_POST["is_submit"]) && isset($_SESSION["set_token"]) && $_POST["get_token"] == $_SESSION["set_token"]){
        $class = explode(".", $_POST["ctrl"])[0];
        $func = explode(".", $_POST["ctrl"])[1];
        include __DIR__.'/controllers/'.$class.'.php';
        if($class == "User"){
            if($func == "login"){
                $user = new User($_POST['username'], $_POST['password']);
                $user->login();
            }else if($func == "logout"){
                $user = new User;
                $user->logout();
            }
        }else if($class == "Contacts"){
            $event = new Contacts;
            if($func == "add"){
                $event->add($_POST['name'],$_POST['surname'],$_POST['tel'],$_POST['email']);
            }else if($func == "delete"){
                $event->delete($_POST['set_id']);
            }else if($func == "get"){
                $contactData = $event->get($_POST['set_id']);
            }else if($func == "update"){
                $event->update($_POST['set_id'],$_POST['name'],$_POST['surname'],$_POST['tel'],$_POST['email']);
            }
        }else if($class == "Sort"){
            $sort = new Sort;
            $desk = $sort->sortBy($_POST['sort_value']);
        }
    }

    if(!isset($_SESSION['user'])){
        $view = "login";
    }else{
        $user = $_SESSION['user'];
        $view = "list";
    }

    include __DIR__.'/resources/views/'.$view.'.php';
    include __DIR__.'/resources/js/script.php';
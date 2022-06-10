<?php
    include __DIR__.'/Controller.php';

    class User implements Controller{
        private $username;
        private $password;
        public function __construct($username=null, $password=null) {
            $this->username = $username;
            $this->password = $password;
        }

        public function login(){
            $db = Factor::getDB();
            $db->table('users');
            $db->select("COUNT(*)");
            $db->where("type = '$this->username' AND password = '".md5("PASS:".$this->password)."'");
            $db->setDB();
            if($db->loadResult()){
                $db = Factor::getDB();
                $db->table('users');
                $db->select("type, permission");
                $db->where("type = '$this->username' AND password = '".md5("PASS:".$this->password)."'");
                $db->setDB();
                $_SESSION['user'] = $db->loadAssoc();
            }else{
                Factor::setMessage('Incorrect username or password.');
            }
        }

        public function logout(){
            session_destroy();
            header("Refresh:0");
        }
    }
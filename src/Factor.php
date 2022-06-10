<?php 
    defined('EXEC') or die;
    require_once __DIR__.'/SetDB/DB.php';

    class Factor
    {
        public static function getDB(){
            return new DB;
        }

        public static function setMessage($message){
            echo '<div class="profile-modal"><div class="pm-content"><span class="close_btn close">x</span><p>'.$message.'</p></div></div>';
        }
    }
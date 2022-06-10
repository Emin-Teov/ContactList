<?php
    include __DIR__.'/Controller.php';

    class Contacts implements Controller{
        public function add($name, $surname, $tel, $email){
            $db = Factor::getDB();
            $db->table('contacts');
            $db->select("COUNT(*)");
            $db->where("phone = '$tel'");
            $db->setDB();
            $phone = $db->loadResult();

            $db = Factor::getDB();
            $db->table('contacts');
            $db->select("COUNT(*)");
            $db->where("email = '$email'");
            $db->setDB();
            $mail = $db->loadResult();

            if($phone){
                Factor::setMessage('This phone number already exists.');
            }else if($mail){
                Factor::setMessage('This email adress already exists.');
            }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Factor::setMessage('Please enter the correct email.');
            }else{
                $db = Factor::getDB();
                $db->table('contacts');
                $db->insert(array('name', 'surname', 'phone', 'email', 'created_at'), array($name, $surname, $tel, $email, time()));
                $db->setDB();   
            }
        }

        public function delete($id){
            $db = Factor::getDB();
            $db->table('contacts');
            $db->delete('id = '.$id);
            $db->setDB();
        }

        public function get($id){
            $db = Factor::getDB();
            $db->table('contacts');
            $db->where('id = '.$id);
            $db->setDB();
            return $db->loadAssoc();
        }

        public function update($id, $name, $surname, $tel, $email){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $db = Factor::getDB();
                $db->table('contacts');
                $db->update(array('name', 'surname', 'phone', 'email', 'updated_at'), array($name, $surname, $tel, $email, time()));
                $db->where('id = '.$id);
                $db->setDB();   
            }else{
                Factor::setMessage('Please enter the correct email.');
            }
        }
    }
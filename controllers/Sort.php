<?php
    class Sort{
        public $sort_array = array('id'=>"By created date",'name'=>"By name", 'surname'=>"By surname", 'email'=>"By mail", 'phone'=>"By phone");

        public function sortBy($sort){
            if(array_key_exists($sort, $this->sort_array)){
                return $sort;
            }else{
                return 'id';
            }
        }
    }
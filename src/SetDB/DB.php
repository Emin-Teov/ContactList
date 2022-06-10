<?php
    class DB
    {
        private $table;
        private $where;
        private $limit;
        private $join;
        private $order;
        private $result;
        private $delete;
        private $setQuery = "";
        private $select = "*";
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "list_db";
        private $insert = array();
        private $quote = array();
        private $update = array();

        public function setQuery($query){
            $this->setQuery = $query;
        }

        public function table($name){
            $this->table = $name;
        }

        public function where($where){
            $this->where = $where;
        }

        public function select($select){
            $this->select = $select;
        }

        public function delete($delete){
            $this->delete = $delete;
        }

        public function insert($insert, $quote){
            $this->insert = $insert;
            $this->quote = $quote;
        }

        public function update($update, $quote){
            $this->update = $update;
            $this->quote = $quote;
        }

        public function inner($name, $quote){
            $this->join = " INNER JOIN ".$name." ON ".$quote;
        }

        public function left($name, $quote){
            $this->join = " LEFT JOIN ".$name." ON ".$quote;
        }

        public function right($name, $quote){
            $this->join = " RIGHT JOIN ".$name." ON ".$quote;
        }

        public function order($order){
            $this->order = " ORDER BY ".$order;
        }

        public function limit($limit){
            $this->limit = " LIMIT ".$limit;
        }

        private function toObject($array) {
            $object = new stdClass();
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $value = $this->toObject($value);
                }
                $object->$key = $value;
            }
            return $object;
        }

        public function setDB($boll=true){
            if($boll){
                try {
                    $showQuery = false;
                    $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    if(is_array($this->insert)&&!empty($this->insert)&&is_array($this->quote)&&!empty($this->quote)){
                        $this->setQuery = "INSERT INTO ".$this->table." (";
                        foreach ($this->insert as $key => $value) {
                            $this->setQuery .= $value;
                            if($key != count($this->insert)-1){
                                $this->setQuery .= ",";
                            }
                        }
                        $this->setQuery .= ") VALUES (";
                        foreach ($this->quote as $key => $value) {
                            $this->setQuery .= "'".$value."'";
                            if($key != count($this->quote)-1){
                                $this->setQuery .= ",";
                            }
                        }
                        $this->setQuery .= ") ";
                    }else if(is_string($this->delete)){
                        $this->setQuery = "DELETE FROM ".$this->table." WHERE ".$this->delete;
                    }

                    if(strlen($this->setQuery)>0){
                        $conn->exec($this->setQuery);
                        return true;
                    }else{
                        $showQuery = true;
                    }
                    
                    if($showQuery){
                        $this->setQuery = "UPDATE ".$this->table." SET ";
                        if(is_string($this->update)){
                            $this->setQuery .= $this->update." = '".$this->quote."'";
                        }else if(is_array($this->update) && !empty($this->update)){
                            foreach ($this->update as $key => $value) {
                                $this->setQuery .= $value." = '".$this->quote[$key]."'";
                                if($key != count($this->update)-1){
                                    $this->setQuery .= ",";
                                }
                            }
                        }else{
                            $this->setQuery = "SELECT ";
                            if(is_array($this->select) && !empty($this->select)){
                                foreach ($this->select as $key => $value) {
                                    $this->setQuery .= $value;
                                    if($key != count($this->select)-1){
                                        $this->setQuery .= ",";
                                    }
                                }
                            }else{
                                $this->setQuery .= $this->select." FROM ".$this->table;
                            }
                        }
                        if(isset($this->where)){
                            $this->setQuery .= " WHERE ".$this->where;
                        }
                        if(isset($this->join)){
                            $this->setQuery .= $this->join;
                        }
                        if(isset($this->order)){
                            $this->setQuery .= $this->order;
                        }
                        if(isset($this->limit)){
                            $this->setQuery .= $this->limit;
                        }

                        $stmt = $conn->prepare($this->setQuery);
                        $stmt->execute();
                        $setResult = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                        $this->reult = $stmt->fetchAll();
                    }
                }catch(PDOException $e) {
                    die("<h4>Query: ". $this->setQuery . "</h4><h4>Error: " . $e->getMessage()."</h4>");
                }
            }
        }

        public function loadResult(){
            return $this->loadRow()[0];
        }

        public function loadAssocList($key=NULL){
            if(is_null($key)){
                $setResult = $this->reult;
            }else{
                $setResult = array();
                foreach ($this->reult as $value) {
                    $setResult[$value[$key]] = $value;
                }
            }
            return $setResult;
        }

        public function loadAssoc(){
            return $this->reult[0];
        }

        public function loadRowList($key=NULL){
            $setResult = array();
            if(is_null($key)){
                foreach ($this->reult as $value) {
                    array_push($setResult, array_values($value));
                }
            }else{
                foreach ($this->reult as $value) {
                    $setResult[$value[$key]] = array_values($value);
                }
            }
            return $setResult;
        }

        public function loadRow(){
            return array_values($this->reult[0]);
        }

        public function loadObjectList($key=NULL){
            if(is_null($key)){
                return $this->toObject($this->reult);
            }else{
                return $this->toObject($this->loadAssocList($key));
            }
        }

        public function loadObject(){
            return $this->toObject($this->reult[0]);
        }
    }
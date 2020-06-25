<?php
require 'conn.php';
class Eager {
        function __construct($main_table, $order_by, $sub_table, $sub_table_selector, $first_param, $second_param, $state, $db_username, $db_password) {
		$this->main_table = $main_table;
                $this->order_by = $order_by;
                $this->sub_table = $sub_table;
                $this->sub_table_selector = $sub_table_selector;
                $this->first_param = $first_param;
                $this->second_param = $second_param;
                $this->state = $state;
                $this->db_username = $db_username;
                $this->db_password = $db_password;
        }
        private function getPDO(){
                $conn = new PDO('mysql:host=127.0.0.1;dbname=binary_city', $this->db_username,$this->db_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
        
        function output(){
                $stmt = $this->getPDO()->prepare("SELECT * FROM $this->main_table ORDER BY $this->order_by ASC");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_UNIQUE);
                if(!empty($result)){
                        $ids = array_column($result, $this->sub_table_selector);
                        $in   = str_repeat('?,', count($ids) - 1) . '?';
                        $stmt = $this->getPDO()->prepare("SELECT $this->first_param, $this->second_param FROM $this->sub_table WHERE $this->sub_table_selector IN ($in)");
                        $stmt->execute($ids);
                        $sub_items = $stmt->fetchAll(PDO::FETCH_GROUP);
                        foreach ($result as $row){
                                $items = $sub_items[$row[$this->sub_table_selector]] ?? null;
                                if($this->state == 'big'){
                                        echo "<br><li><p style='font-size:20px'> " .  $row[$this->order_by] . " " . $row[$this->sub_table_selector] . "&nbsp;&nbsp;&nbsp;<span><button class='btn btn-primary link_contact'>Link ". $this->main_table . "</button></span></p><ul>";
                                }else{
                                        echo "<li> " . $row[$this->order_by] . " " . $row[$this->sub_table_selector] . "<ul>";
                                }
                                
                                if (is_array($items)){
                                        foreach($items as $item){
                                                if($this->state == 'big'){
                                                        echo "<li> " . $item[$this->second_param] . "</li><button class='btn btn-danger' type='delete' onclick='location.href=\"unlink.php?" . $this->first_param . "="  .     $row[$this->first_param]  . "&" . $this->second_param . "=" . $item[$this->second_param] . "\";'>Remove link</button>";
                                                }
                                                else{
                                                        echo "<li> " . $item[$this->second_param] . "</li>";
                                                }
                                        }
                                }
                                echo "</ul></li>";
                                if($this->state == 'big'){
                                        echo " </li><form style='display: none;'class='search_form' autocomplete='off'>";
                                        echo "<input type='text' class= 'search'>";
                                        echo "<button class='btn btn-success' type='submit'>Link</button>";
                                        echo "</form>";
                                }
                                
                        }
                }else{
                        echo "No ".$this->main_table . " found.";
                }  

        }

}  

?>
<?php

class UserProfile {
    public $name = "silly bob";
    public $role = 0;
    public $type = 0;
    public $email = "test_this@test.com";

    public function __construct($parameters = array()) {
        foreach($parameters as $key => $value) {
            $this->$key = $value;
        }
    }
    public function print_user(){
        echo "<pre>";
        print_r($this);
        echo "</pre>";
    }
    public function getName(){
        echo $this->name;
    }
}

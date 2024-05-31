<?php
 class employees{
    public $id;
    public $name;
    public $age;
    public $address;
    public $tax;
    public $salary;
    public function __construct($name,$age,$tax,$salary){
        $this->name=$name;
        $this->age=$age;
        $this->tax=$tax;
        $this->salary=$salary;
    }
    function calculatesalary(){
        return $this->salary-($this->salary*$this->tax);
    }
 };
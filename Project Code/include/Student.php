<?php

class Student {
    // property declaration
    public $userid;
    public $password;    
    public $name;
    public $school;
    public $edollar;
    
    public function __construct($username='', $password='', $name='', $school='', $edollar='') {
        $this->userid = $username;
        $this->password = $password;
        $this->name = $name;
        $this->school = $school;
        $this->edollar = $edollar;
    }
    
    public function authenticate($enteredPwd) {
        return $enteredPwd === $this->password;
    }

    public function adminLogin($enteredPwd) {
        return $enteredPwd === 'Ellesse101@';
    }
}

?>
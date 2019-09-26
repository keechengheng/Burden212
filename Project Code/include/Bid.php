<?php

class Bid {
    // property declaration
    public $userid;    
    public $amount;
    public $courseid;
    public $section;
    
    public function __construct($userid='', $amount='', $courseid='', $section='') {
        $this->userid = $userid;
        $this->amount = $amount;
        $this->courseid = $courseid;
        $this->section = $section;
    }
}

?>
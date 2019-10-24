<?php

class BiddingResults {
    // property declaration
    public $userid;    
    public $courseid;
    public $section;
    public $round;
    public $dtvalue;
    public $amount;
    public $status;
    
    public function __construct($userid='', $courseid='', $section='', $round='', $dtvalue='', $amount='',$status='') {
        $this->userid = $userid;
        $this->courseid = $courseid;
        $this->section = $section;
        $this->round = $round;
        $this->dtvalue = $dtvalue;
        $this->amount = $amount;
        $this->status = $status;
    }
}

?>
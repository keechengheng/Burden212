<?php

class BiddingResults {
    // property declaration
    public $userid;    
    public $courseid;
    public $section;
    public $round;
    public $datetime;
    public $amount;
    public $status;
    
    public function __construct($userid='', $courseid='', $section='', $round='', $datetime='', $amount='',$status='') {
        $this->userid = $userid;
        $this->courseid = $courseid;
        $this->section = $section;
        $this->round = $round;
        $this->datetime = $datetime;
        $this->amount = $amount;
        $this->status = $status;
    }
}

?>
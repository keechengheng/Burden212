<?php

class Prerequisite {
    // property declaration
    public $courseid;    
    public $prerequisiteid;
    
    public function __construct($courseid='', $prerequisiteid='') {
        $this->courseid = $courseid;
        $this->prerequisiteid = $prerequisiteid;
    }
}

?>
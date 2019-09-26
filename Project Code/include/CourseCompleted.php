<?php

class CourseCompleted {
    // property declaration
    public $userid;    
    public $courseid;
    
    public function __construct($userid='', $courseid='') {
        $this->userid = $userid;
        $this->courseid = $courseid;
    }
}

?>
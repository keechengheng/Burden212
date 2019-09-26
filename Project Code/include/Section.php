<?php

class Section {
    // property declaration
    public $courseid;
    public $sectionid;    
    public $day;
    public $start;
    public $end;
    public $instructor;
    public $venue;
    public $size;
    
    public function __construct($courseid='', $section='', $day='', $start='', $end='', $instructor='', $venue='', $size='') {
        $this->courseid = $courseid;
        $this->sectionid = $section;
        $this->day = $day;
        $this->start = $start;
        $this->end = $end;
        $this->instructor = $instructor;
        $this->venue = $venue;
        $this->size = $size;
    }
}

?>
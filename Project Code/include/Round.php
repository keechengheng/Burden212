<?php

class Round {
    // property declaration
    public $rowid;    
    public $roundid;
    public $statusid; //0 - closed, 1 - opened
    
    public function __construct($rowid='', $roundid='', $statusid='') {
        $this->rowid = $rowid;
        $this->roundid = $roundid;
        $this->statusid = $statusid;
    }
}

?>
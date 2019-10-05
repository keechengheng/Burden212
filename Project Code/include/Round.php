<?php

class Round {
    // property declaration
    public $rowid;    
    public $roundid;
    
    public function __construct($rowid='', $roundid='') {
        $this->rowid = $rowid;
        $this->roundid = $roundid;
    }
}

?>
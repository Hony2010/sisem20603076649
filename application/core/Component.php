<?php

class MY_Component extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        log_message('info', 'Component Class Initialized');
    }
    
}

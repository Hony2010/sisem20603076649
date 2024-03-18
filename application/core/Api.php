<?php

class MY_Api extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        log_message('info', 'Api Class Initialized');
    }

}

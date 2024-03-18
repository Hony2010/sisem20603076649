<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once("Services/autoload.php");

class ServicesSearch{

  public $CI;

  function __construct()
  {
      //parent::__construct();
      /*Additional code which you want to run automatically in every function call */
      if (!isset($this->CI))
      {
          $this->CI =& get_instance();
      }

      $this->search = new \Search\Search();
  }

  function ruc($number)
  {
    return  $this->search->ruc($number);
  }

  function dni($number)
  {
    return  $this->search->dni($number);
  }
}

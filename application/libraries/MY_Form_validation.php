<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation  {

    function __construct()
    {
        parent::__construct();  
    }

    public function valid_datetime($date_time)
    {

        // Validate datetime
        $format = 'Y-m-d H:i:s'; 
        $d = DateTime::createFromFormat($format, $date_time);
        $valid = $d && $d->format($format) == $date_time;

        if ($valid)
        {
            return TRUE;
        } 
        else
        {
            $this->set_message('valid_datetime', 'Invalid date');
            return FALSE;
        }
    }
}
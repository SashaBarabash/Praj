<?php
/**
 * Created by PhpStorm.
 * User: rebit
 * Date: 02.11.2015
 * Time: 11:20
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/Requests/Requests.php";
class PHPRequests {
    public function __construct() {
        Requests::register_autoloader();
    }
}
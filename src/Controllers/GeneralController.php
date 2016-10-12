<?php
/**
 * Created by PhpStorm.
 * User: afranco
 * Date: 5/23/2016
 * Time: 4:24 PM
 */
namespace App\Controller;
use MartynBiz\Slim3Controller\Controller;
class GeneralController extends  Controller
{
    public function get_base_path()
    {
        return $this->request->getUri()->withPath('');
    }
}
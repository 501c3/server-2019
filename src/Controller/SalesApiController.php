<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 3/8/19
 * Time: 11:26 AM
 */

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

class SalesApiController extends BaseController
{
    /**
     * @Route("/api/sales", name="api_sales")
     */
    public function salesApi() {
        return $this->json(['api_sales'=>'TODO: upload angular application']);
    }

    /**
     * @Route("/api/sales/home", name="api_sales_home")
     */

    public function salesApiHome() {
        return $this->json(['api_sales_home'=>'TODO: connect to video tutorial']);
    }
}
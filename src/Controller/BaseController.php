<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/9/19
 * Time: 3:21 PM
 */

namespace App\Controller;
use App\Entity\Access\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


abstract class BaseController extends AbstractController
{
    /**
    * @Route("/", name="home_page")
    */
    public function index()
    {
        return $this->render('base.html.twig');
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/api/sales", name="api_sales")
     */
    public function sales()
    {
        return $this->render('base');
    }
}
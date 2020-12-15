<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PDO;

class DefaultController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('homepage.html.twig');

    }
    
}
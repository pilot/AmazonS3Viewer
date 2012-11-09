<?php

namespace AmazonS3\ViewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ViewBundle:Default:index.html.twig', array());
    }
}
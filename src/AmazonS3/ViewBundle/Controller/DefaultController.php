<?php

namespace AmazonS3\ViewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $amazon = $this->get('s3_view.service');
        $amazon->registerStreamWrapper('s3');

        $finder = new Finder();
        $finder->name('images*')->size('< 100K')->date('since 1 day ago');

        return $this->render('ViewBundle:Default:index.html.twig', array(
            'images' => $finder->in('s3://bucket-name')
        ));
    }
}

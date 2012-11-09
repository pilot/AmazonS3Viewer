<?php

namespace AmazonS3\ViewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $amazon = $this->get('s3_view.service');
        $amazon->registerStreamWrapper('s3');

        $finder = new Finder();
        $finder->name('*-thumb.jpg')->size('< 100K')->date('since 1 day ago');

        return $this->render('ViewBundle:Default:index.html.twig', array(
            'images' => $finder->in('s3://'.$this->container->getParameter('s3_view.bucket_name'))
        ));
    }

    public function imageAction($name)
    {
        $amazon = $this->get('s3_view.service');

        $name = $this->container->getParameter('s3_view.bucket_name').'/'.str_replace('-thumb', '-org', $name);
        if (!$amazon->isObjectAvailable($name)) {
            throw $this->createNotFoundException(sprintf('Image %s was not found.', $name));
        }

        return new Response($amazon->getObject($name));
    }
}

<?php

namespace AmazonS3\ViewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $images = $this->get('s3_view.service')->getObjectsByBucket(
            $this->container->getParameter('s3_view.bucket_name'),
            array(
                 'delimiter' => '-org.jpg',
                 'max-keys'  => 15,
                 'marker'    => $request->query->get('marker')
            )
        );

        // to prevent displaying directory
        array_shift($images);

        return $this->render('ViewBundle:Default:index.html.twig', array(
            'images' => $images ?: array(),
            'url'    => $this->container->getParameter('s3_view.cdn_url')
        ));
    }

    public function imageAction($name)
    {
        $amazon = $this->get('s3_view.service');

        $fullname = $this->container->getParameter('s3_view.bucket_name').'/'.$name;
        if (!$amazon->isObjectAvailable($fullname)) {
            throw $this->createNotFoundException(sprintf('Image "%s" was not found.', $fullname));
        }

        return $this->render('ViewBundle:Default:image.html.twig', array(
            'name'   => $name,
            'url'    => $this->container->getParameter('s3_view.cdn_url')
        ));
    }
}

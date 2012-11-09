<?php

namespace AmazonS3\ViewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $images = $this->get('s3_view.service')->getObjectsByBucket(
            $this->container->getParameter('s3_view.bucket_name'),
            array(
                 'delimiter' => '-org.jpg',
                 'max-keys'  => $this->container->getParameter('items_per_page'),
                 'marker'    => $request->query->get('marker')
            )
        );
        $images = $images ?: array();

        // to prevent displaying directory
        if (isset($images[0]) && false === stripos($images[0], '-thumb')) {
            array_shift($images);
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('ViewBundle:Default:_list.html.twig', array(
                 'images' => $images,
                 'url'    => $this->container->getParameter('s3_view.cdn_url')
            ));
        }

        return $this->render('ViewBundle:Default:index.html.twig', array(
            'images' => $images,
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

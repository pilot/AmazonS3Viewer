<?php

namespace AmazonS3\ViewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $images = $this->get('s3_view.service')->getObjectsByBucket(
            $this->container->getParameter('s3_view.bucket_name'),
            array(
                 'delimiter' => '-thumb.jpg',
                 'max-keys'  => 3,
                 'marker'    => $request->query->get('marker')
            )
        );

        return $this->render('ViewBundle:Default:index.html.twig', array(
            'images' => $images ?: array()
        ));
    }

    public function thumbAction(Request $request,$name)
    {
        $amazon = $this->get('s3_view.service');

        $name = $this->container->getParameter('s3_view.bucket_name').'/'.$name;
        if (!$info = $amazon->getInfo($name)) {
            throw $this->createNotFoundException(sprintf('Image "%s" was not found.', $name));
        }

        $response = $this->createImageResponse($info);
        if ($response->isNotModified($request)) {
            return $response->send();
        }

        $response->setContent($amazon->getObject($name));

        return $response;
    }

    public function imageAction(Request $request, $name)
    {
        $amazon = $this->get('s3_view.service');

        $name = $this->container->getParameter('s3_view.bucket_name').'/'.str_replace('-thumb', '-org', $name);
        if (!$info = $amazon->getInfo($name)) {
            throw $this->createNotFoundException(sprintf('Image "%s" was not found.', $name));
        }

        $response = $this->createImageResponse($info);
        if ($response->isNotModified($request)) {
            return $response->send();
        }

        $response->setContent($amazon->getObject($name));

        return $response;
    }

    private function createImageResponse(array $info)
    {
        $response = new Response('', 200, array('Content-Type' => $info['type']));
        $response->setLastModified(new \DateTime('@'.$info['mtime']));
        $response->setExpires(new \DateTime('-30 days'));

        return $response;
    }
}

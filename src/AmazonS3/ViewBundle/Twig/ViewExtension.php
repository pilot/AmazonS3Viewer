<?php

namespace AmazonS3\ViewBundle\Twig;

use Twig_Extension;
use Twig_Function_Method;

class ViewExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'get_image_thumb' => new \Twig_Function_Method($this, 'getImageThumb'),
        );
    }

    public function getImageThumb($name)
    {
        return str_replace('-org', '-thumb', $name);
    }

    public function getName()
    {
        return 'amazon_view';
    }
}
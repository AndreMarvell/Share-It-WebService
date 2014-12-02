<?php

namespace Covoiturage\RateCommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CovoiturageRateCommentBundle:Default:index.html.twig', array('name' => $name));
    }
}

<?php

namespace Covoiturage\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{
  public function getUserAction($id){
      
    $em   = $this->getDoctrine()->getManager();
    
    $user =   $em->getRepository('CovoiturageUserBundle:Users')->findOneById($id);
    
    if(!is_object($user)){
      throw $this->createNotFoundException();
    }
    
    return $user;
  }
}

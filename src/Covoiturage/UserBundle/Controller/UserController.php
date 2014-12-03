<?php

namespace Covoiturage\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends Controller
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer un user par son identifiant",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de l'utilisateur"
     *      }
     *  },
     * )
     */
    public function getUserAction($id){

      $em   = $this->getDoctrine()->getManager();

      $user =   $em->getRepository('CovoiturageUserBundle:Users')->findOneById($id);

      if(!is_object($user)){
        throw $this->createNotFoundException();
      }

      return $user;
    }
}

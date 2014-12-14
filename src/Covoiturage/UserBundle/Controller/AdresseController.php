<?php

namespace Covoiturage\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\RedirectView;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use Covoiturage\UserBundle\Entity\Adresse;
use Covoiturage\UserBundle\Form\AdresseType;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AdresseController extends \FOS\RestBundle\Controller\FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer une adresse par son identifiant",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de l'adresse"
     *      }
     *  },
     * )
     */
    public function getAdresseAction($id){

      $em   = $this->getDoctrine()->getManager();

      $adresse =   $em->getRepository('CovoiturageUserBundle:Adresse')->findOneById($id);

      if(!is_object($adresse)){
        throw $this->createNotFoundException();
      }
      return $adresse;
    }
    

}

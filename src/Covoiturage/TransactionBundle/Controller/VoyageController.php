<?php

namespace Covoiturage\TransactionBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class VoyageController extends Controller
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer un voyage par son identifiant",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du voyage"
     *      }
     *  },
     * )
     */
    public function getVoyageAction($id){

      $em   = $this->getDoctrine()->getManager();

      $voyage =   $em->getRepository('CovoiturageTransactionBundle:Voyage')->findOneById($id);

      if(!is_object($voyage)){
        throw $this->createNotFoundException();
      }

      return $voyage;
    }
}

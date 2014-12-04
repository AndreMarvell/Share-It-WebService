<?php

namespace Covoiturage\TransactionBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AnnoncesController extends Controller
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer une annonce par son identifiant",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de l'annonce"
     *      }
     *  },
     * )
     */
    public function getAnnonceAction($id){

      $em   = $this->getDoctrine()->getManager();

      $annonce =   $em->getRepository('CovoiturageTransactionBundle:Annonce')->findOneById($id);

      if(!is_object($annonce)){
        throw $this->createNotFoundException();
      }

      return $annonce;
    }
}

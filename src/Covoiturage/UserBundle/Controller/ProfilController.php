<?php

namespace Covoiturage\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\RedirectView;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Covoiturage\RateCommentBundle\Entity\Comment;
use Covoiturage\RateCommentBundle\Form\CommentType;
use Covoiturage\RateCommentBundle\Entity\Rate;
use Covoiturage\RateCommentBundle\Form\RateType;



use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProfilController extends \FOS\RestBundle\Controller\FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer un conducteur par son identifiant",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du conducteur"
     *      }
     *  },
     * )
     */
    public function getConducteurAction($id){

      $em   = $this->getDoctrine()->getManager();

      $entity =   $em->getRepository('CovoiturageUserBundle:Conducteur')->findOneById($id);

      if(!is_object($entity)){
        throw $this->createNotFoundException();
      }else{
        $comments = $em->getRepository('CovoiturageRateCommentBundle:Comment')->findByThread($entity->getComments()); 
        return array("conducteur"=>$entity,"comments"=>$comments);
      }
      
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer un passager par son identifiant",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du passager"
     *      }
     *  },
     * )
     */
    public function getPassagerAction($id){

      $em   = $this->getDoctrine()->getManager();

      $entity =   $em->getRepository('CovoiturageUserBundle:Passager')->findOneById($id);

      if(!is_object($entity)){
        throw $this->createNotFoundException();
      }else{
        $comments = $em->getRepository('CovoiturageRateCommentBundle:Comment')->findByThread($entity->getComments()); 
        return array("passager"=>$entity,"comments"=>$comments);
      }
      
    }
    
    
    

}

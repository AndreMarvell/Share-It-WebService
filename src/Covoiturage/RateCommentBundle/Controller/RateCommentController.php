<?php

namespace Covoiturage\RateCommentBundle\Controller;

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

class RateCommentController extends \FOS\RestBundle\Controller\FOSRestController
{
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Commenter un conducteur ou un passager",
     *  input="Covoiturage\RateCommentBundle\Form\CommentType",
     *  output="Covoiturage\RateCommentBundle\Entity\Comment",
     * )
     */
    public function postCommentAction(Request $request)
    {
        $entity = new Comment();

        

        $form = $this->createForm(new CommentType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            return $entity;
        }
        return array(
            'form' => $form,
        );
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Noter un conducteur ou un passager",
     *  input="Covoiturage\RateCommentBundle\Form\RateType",
     *  output="Covoiturage\RateCommentBundle\Entity\Rate",
     * )
     */
    public function postRateAction(Request $request)
    {
        $entity = new Rate();

        

        $form = $this->createForm(new RateType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            return $entity;
        }
        return array(
            'form' => $form,
        );
    }
    
    
    

}

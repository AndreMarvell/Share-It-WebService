<?php

namespace Covoiturage\TransactionBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Covoiturage\TransactionBundle\Entity\Demande;
use Covoiturage\TransactionBundle\Form\DemandeType;

class DemandesController extends Controller
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer la liste de demandes faite par un passager",
     *  requirements={
     *      {
     *          "name"="passengerId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du passager qui veut récupérer la liste de ses demandes"
     *      }
     *  },
     * )
     */
    public function getPassagerListeDemandesAction($passengerId){
        // get the entity manager and the repository of demande. The repository will handle connections
        // bound it to demande entity.
        $demandRepos = $this->getDoctrine()->getRepository('CovoiturageTransactionBundle:Demande');
        $listeDemandes = $demandRepos->findByPassager($passengerId);
        // We suppose that if there is at least one data, it will return an array, and nothing if not found
        if(empty($listeDemandes)){
            throw $this->createNotFoundException('Do not try to hack me. Have already done it when it had your age');
        }
        return $listeDemandes;
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer la liste de demandes associées à une annonce",
     *  requirements={
     *      {
     *          "name"="annonceId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de l'annonce dont on veut voir les demandes associées"
     *      }
     *  },
     * )
     */
    public function getAnnonceListeDemandesAction($annonceId){
        // get the entity manager and the repository of demande. The repository will handle connections
        // bound it to demande entity.
        $demandRepos = $this->getDoctrine()->getRepository('CovoiturageTransactionBundle:Demande');
        $listeDemandes = $demandRepos->findByAnnonce($annonceId);
        // We suppose that if there is at least one data, it will return an array, and nothing if not found
        if(empty($listeDemandes)){
            throw $this->createNotFoundException('Do not try to hack me. Have already done it when it had your age');
        }
        return $listeDemandes;
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Consulter une demande en tant que conducteur ou passager",
     *  requirements={
     *      {
     *          "name"="demandeId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de la demande dont on veut voir les commentaires associées"
     *      }
     *  },
     * )
     */
    public function getDemandeAction($demandeId){
        $em   = $this->getDoctrine()->getManager();

        $demande =   $em->getRepository('CovoiturageTransactionBundle:Demande')->findOneById($demandeId);

        if(!is_object($demande)){
          throw $this->createNotFoundException();
        }else{
          $comments = $em->getRepository('CovoiturageRateCommentBundle:Comment')->findByThread($demande->getComments()); 
          
          return array("Demande"=>$demande,"comments"=>$comments);
        }
    }
    
     /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Faire une demande sur une annonce. Limite, 10 Demandes par annonces. Vérifier la redirection",
     *  input="Covoiturage\TransactionBundle\Form\DemandeType",
     *  output="Covoiturage\TransactionBundle\Entity\Demande",
     * )
     */
    public function postUserDemandAction(Request $request)
    {
        $userToken = $request->request->get('token'); // Symphony va t'il reconnaitre
        // les éléments du formulaire ? (si mélangé parmi beaucoup d'autres qui ne sont du formulaire.
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository('CovoiturageUserBundle:Users')->findOneByToken($userToken);

        if(is_object($user)){
            $demandeEntity = new Demande();
            $form = $this->createForm(new DemandeType(), $demandeEntity);
            $annonce = $entityManager->getRepository('CovoiturageTransactionBundle:Annonce')
                        ->findOneById($request->request->get('annonce'));
            if (!is_object($annonce)){
                throw $this->createNotFoundException("Ne m'embète pas!");
            }
            
            $demandeEntity->setAnnonce($annonce);
            $form->bind(array(
                "message" => $request->request->get('message'),
                "prix" => $request->request->get('prix'),
                //"annonce" => $annonce,
                "arrivee_propose" => $request->request->get('arrivee_propose')
            ));
            
            if ($form->isValid()){
                //Vérifier la date, demande pas possible si moins de 5 min du départ.
                $minute = $demandeEntity->getAnnonce()->getDateDepart()->diff($demandeEntity->getDate());
                $minute = ($minute->d*24*60 + $minute->h*60+ $minute->i);
                if($minute < 5){
                    throw new $this->createAccessDeniedException("Le départ de ce voyage est prévu dans moins de 5 min");
                }
                // Une seule demande par annonce
                $passager = $entityManager->getRepository('CovoiturageUserBundle:Passager')->findOneByUser($user);
                if(!is_object($passager)){
                    $passager = new \Covoiturage\UserBundle\Entity\Passager($user);
                    $entityManager->persist($passager);
                }
                $demandeEntity->setPassager($passager);
                $entityManager->persist($demandeEntity);
                $entityManager->flush();
                $demandeEntity->getPassager()->createThread();
                $demandeEntity->createThread();
                $entityManager->persist($demandeEntity);
                $entityManager->flush();
                
                return $demandeEntity;
            }
            return array(
                'form' => $form,
            );
        }
        
    }
}

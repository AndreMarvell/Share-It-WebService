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
     *  },
     * )
     */
    public function getPassager_liste_demandesAction(Request $request){
        // get the entity manager and the repository of demande. The repository will handle connections
        // bound it to demande entity.
        // Un conducteur, s'il veut voir les demandes, passe par les annonces
        // Voir les demandes faites. Le token identifie déjà le passager
        
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $this->getDoctrine()->getRepository('CovoiturageUserBundle:Passager')->createQueryBuilder('p');
        $passager = $qb->join('p.user', 'u')
                            ->addSelect('u')
                            ->where('u.token = :token')
                            ->setParameter('token', $request->query->get("token"))
                            ->setMaxResults(1) // SQL LIMIT 1
                            ->getQuery()
                            ->getResult();
        
        if (empty($passager)){
            throw $this->createAccessDeniedException("LOL, MEN");
        }
        $demandRepos = $this->getDoctrine()->getRepository('CovoiturageTransactionBundle:Demande');
        $listeDemandes = $demandRepos->findByPassager($passager);
        // We suppose that if there is at least one data, it will return an array, and nothing if not found
        return $listeDemandes;
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer la liste de demandes associées à une annonce. Seul le conducteur peut voir les demandes associées à une annonce",
     *  requirements={
     *      {
     *          "name"="annonceId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de l'annonce dont on veut voir les demandes associées."
     *      }
     *  },
     * )
     */
    public function getAnnonce_liste_demandesAction(Request $request, $annonceId){
        // get the entity manager and the repository of demande. The repository will handle connections
        // bound it to demande entity.
        // Vérifier que l'annonce appartient au conducteur qui veut voir les demandes
        // Seul le conducteur peut voir la liste des demandes associées à son annonce.
        // Les passagers ne peuvent pas voir la liste des demandes associées à une annonce mais peuvent 
        // uniquement voir la liste de leur demandes
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository('CovoiturageUserBundle:Users')->findOneByToken($request->query->get("token"));
        if (!is_object($user)){
            throw $this->createAccessDeniedException("LOL, MEN");
        }
        $annonce = $entityManager->getRepository('CovoiturageTransactionBundle:Annonce')->findOneById($annonceId);
        if($annonce->getConducteur()->getUser() != $user){ // on vérifie que le token envoyé identifie bien 
        //le conducteur de l'annonce en question. Cas où un attaquant envoie un identifiant correct mais un token d'un autre user qui ne 
        //ne peut normalement pas voir l'annonce. Si le token n'est pas conducteur de l'annonce donnée, alors rien.
            throw $this->createAccessDeniedException("How did you get this data ?");
        }
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
    public function getDemandeAction(Request $request, $demandeId){
        // on récupère une demande si on est passager ayant fait la demande ou si on est conducteur de l'annonce en question
        $em   = $this->getDoctrine()->getManager();
        $userToken = $request->query->get('token');
        if (empty($userToken)){
            throw $this->createAccessDeniedException("LOL, MEN");
        }
        // Vérifier que la demande correspond à celle du user en question, sinon un user peut voir toutes les demandes faites.
        // Même si ce ne sont les siennes
        $demande = $em->getRepository('CovoiturageTransactionBundle:Demande')->findOneById($demandeId);
        if(!is_object($demande)){
          throw $this->createNotFoundException();
        }else{
            $isPassenger = true;
            if ($demande->getPassager()->getUser()->getToken() != $userToken){
                if($demande->getAnnonce()->getConducteur()->getUser()->getToken() != $userToken){
                    throw $this->createAccessDeniedException("Not your demands");   
                }
                $isPassenger = false;
            }
            $comments = $em->getRepository('CovoiturageRateCommentBundle:Comment')->findByThread($demande->getComments()); 
            if($isPassenger){
                return array("Annonce" => array("Depart" => $demande->getAnnonce()->getDepart(),
                                                "Arrivee" => $demande->getAnnonce()->getArrivee(),
                                                "Prix" => $demande->getAnnonce()->getPrix(),
                                                "Conducteur_nom" => $demande->getAnnonce()->getConducteur()->getUser()->getNom(),
                                                "Conducteur_prenom" => $demande->getAnnonce()->getConducteur()->getUser()
                                                                                        ->getPrenom(),
                                                "Conducteur_Photo" => $demande->getAnnonce()->getConducteur()->getUser()->getPhoto(),
                                                "Conducteur_Voiture" => $demande->getAnnonce()->getVoiture(),
                                                ),
                         "Arrivee_Propose" => $demande->getArriveePropose(),
                         "date" => $demande->getDate(),
                         "message" => $demande->getMessage(),
                         "archivee" => $demande->getArchivee(),
                         "prix" => $demande->getPrix(),
                         "comments"=>$comments);
            }
            return array("Annonce"=>$demande->getAnnonce(),
                         "Passager"=>$demande->getPassager(),
                         "Arrivee_Propose" => $demande->getArriveePropose(),
                         "date" => $demande->getDate(),
                         "message" => $demande->getMessage(),
                         "archivee" => $demande->getArchivee(),
                         "prix" => $demande->getPrix(),
                         "comments"=>$comments);
        }
    }
    
     /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Faire une demande sur une annonce. Limite, 1 Demandes par annonces. Vérifier la redirection",
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
                $qb = $this->getDoctrine()->getRepository('CovoiturageTransactionBundle:Demande')->createQueryBuilder('d');
                $demande = $qb->join('d.passager', 'p')->addSelect('p')->where('p = :passager')->setParameter('passager', $passager)
                            ->andWhere('d.annonce = :annonce')
                            ->setParameter('annonce', $annonce)
                            ->setMaxResults(1) // SQL LIMIT 1
                            ->getQuery()
                            ->getResult(); // Récupération de la demande déjà faite par un passager.
                
                if (!empty($demande)){
                    throw $this->createAccessDeniedException("Vous ne pouvez faire plus d'une demande par annonce");
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

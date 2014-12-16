<?php

namespace Covoiturage\TransactionBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

use Covoiturage\TransactionBundle\Entity\Voyage;

class VoyageController extends Controller
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer un voyage par son identifiant, Consulter un voyage",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du voyage"
     *      },
     *  },
     * )
     */
    public function getVoyageAction(Request $request, $id){
        // request sert uniquement à éviter des attaques (requête d'un attaquant sans token)
        $em = $this->getDoctrine()->getManager();
        
        $userToken = $request->query->get('token');
        $isDriver = $request->query->get('isConducteur');
        if(empty($userToken) || empty($isDriver)){
            throw $this->createNotFoundException("Do not try to hack me. Have already done it when it had your age");
        }else{
            $emRepos   = $em->getRepository('CovoiturageTransactionBundle:Voyage');
            // Voyage récupéré
            $voyage = new Voyage();
            $voyage =   $emRepos->findOneById($id);

            if(!is_object($voyage)){
                throw $this->createNotFoundException();
            }else{
                if($isDriver){
                    return $voyage;
                }else{
                    // infos annonces, infos conducteur (voiture, nom prenom email et certaines infos), l'entité voyageur
                   foreach ($voyage->getVoyageurs() as $v){
                       // on parcourt la liste des voyageurs et cherche celui dont le token correspond
                       if($v->getPassager()->getUser()->getToken() === $userToken){
                           return array('annonce' => $voyage->getAnnonce(),
                                        'voyageur' => $v);
                       }
                   }
                   throw $this->createNotFoundException();
                }
            }
        } 
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Recupérer la liste des voyages effectués par une personne",
     *  requirements={
     *      
     *  },
     * )
     */    
    public function getListeVoyagesAction(Request $request){
        
        $userToken = $request->query->get('token');
        if (empty($userToken)){
            throw $this->createNotFoundException ("I\'m smartter than ya!! Nygga");
        }else{
            // There is surely a better way of doing this, Just imagine if we have 1200000 users...
            $entityManager = $this->getDoctrine()->getManager();
            
            $voyages = array(); // List of travel done by this user.
            $userRepository = $entityManager->getRepository('CovoiturageUserBundle:Users');
            $user = $userRepository->findOneBy(array('token' => $userToken));
            
            /*$driverAnnonceVoyageRepository = $entityManager->getRepository('CovoiturageUserBundle:Conducteur');
            $driver = $driverAnnonceVoyageRepository->findByUser($user);
            
            $driverAnnonceVoyageRepository = $entityManager->getRepository('CovoiturageTransactionBundle:Annonce');
            $annonces = $driverAnnonceVoyageRepository->findByConducteur($driver);
            
            $driverAnnonceVoyageRepository = $entityManager->getRepository('CovoiturageTransactionBundle:Voyage');

            foreach ($annonces as $annonce){
                $voyage = $driverAnnonceVoyageRepository->findByAnnonce($annonce);
                //$voyage = $driverAnnonceVoyageRepository->findByAnnonce($annonces); // Marche bien mais renvoie tout or on a besoin de chaque annonce individuellement
                // un peu comme find by array of same elements

                if (!empty($voyage)){ // Test if the array is empty. Should get only one value
                    array_push($voyages, array('EstConducteur' => true,
                                               'Date' => $annonce->getDateDepart(),
                                               'Départ' => $annonce->getDepart(),
                                               'Arrivée' => $annonce->getArrivee(),));
                }
            }*/
            
            // Retrieving user data, considered as simple passenger here.
            $passengerTravellerTravel = $entityManager->getRepository('CovoiturageUserBundle:Passager');
            $passenger = $passengerTravellerTravel->findByUser($user);
            
            $passengerTravellerTravel = $entityManager->getRepository('CovoiturageUserBundle:Voyageur');
            $travellers = $passengerTravellerTravel->findByPassager($passenger); // List of traveller values corresponding to each passenger
            
            $passengerTravellerTravel = $entityManager->getRepository('CovoiturageTransactionBundle:Voyage');
            $driverAnnonceVoyageRepository = $entityManager->getRepository('CovoiturageTransactionBundle:Annonce');
            /*foreach ($travellers as $traveller){
                $voyage = $passengerTravellerTravel->findByVoyageurs($traveller);
                
                if(!empty($voyage)){ // One traveller associated to only one travel.
                    // One trip is associated to only one 'annonce'
                    $annonce = $driverAnnonceVoyageRepository->findById($voyage->getAnnonce());
                    array_push($voyages, array('EstConducteur' => false,
                                               'Date' => $annonce->getDateDepart(),
                                               'Départ' => $annonce->getDepart(),
                                               'Arrivée' => $annonce->getArrivee(),));
                }
            }   */
        }   
        return /*$voyages*/ $travellers;
    }
}

<?php

namespace Covoiturage\TransactionBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Covoiturage\TransactionBundle\Entity\Annonce;
use Covoiturage\TransactionBundle\Form\AnnonceType;
use Symfony\Component\HttpFoundation\Request;

class AnnoncesController extends Controller
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer une annonce par son identifiant, Consulter une annonce",
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
    public function getAnnonceAction(Request $request, $id){

      $em   = $this->getDoctrine()->getManager();
      if(!is_object($this->getDoctrine()
              ->getManager()->getRepository("CovoiturageUserBundle:Users")
              ->findOneByToken($request->query->
                      get("token")))){
        throw $this->createAccessDeniedException("HUH ?!");
      }
      $annonce =   $em->getRepository('CovoiturageTransactionBundle:Annonce')->findOneById($id);
      if(!is_object($annonce)){
        throw $this->createNotFoundException();
      }
      return $annonce;
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Recupérer la liste des annonces faites par un conducteur",
     *  requirements={
     *  },
     * )
     */    
    public function getListe_annoncesAction(Request $request){
        // get the entity manager with repository that handle Annonce db connections
        // En gros on a déjà lié et spécifié que l'on traite la table annonce
        // Récupérer l'ensemble des annonces faites par un conducteur.
        // Le token permet déjà d'identifier le conducteur en question.
        
        $entityManager = $this->getDoctrine()->getManager();
        // Get the driver by the token
        $qb = $this->getDoctrine()->getRepository('CovoiturageUserBundle:Conducteur')->createQueryBuilder('c');
        $driver = $qb->join('c.user', 'u')
                ->addSelect('u')
                ->where('u.token = :token')
                ->setParameter('token', $request
                                        ->query
                                        ->get("token"))
                ->setMaxResults(1) // SQL LIMIT 1
                ->getQuery()
                ->getResult();
        $annonceRepos = $entityManager->getRepository('CovoiturageTransactionBundle:Annonce');
        $listeAnnonces = $annonceRepos->findByConducteur($driver);
        
        if(empty($listeAnnonces)){
            throw $this->createNotFoundException();
        }
        
        return $listeAnnonces;
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Recupérer la liste des annonces géolocalisées",
     *  requirements={
     * 
     *  },
     * )
     */
    public function getAnnoncesAction(Request $request){
        // longitude et latitude dans request
        $userToken = $request->query->get('token'); // Symphony va t'il reconnaitre
        // les éléments du formulaire ? (si mélangé parmi beaucoup d'autres qui ne sont du formulaire.
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository('CovoiturageUserBundle:Users')->findOneByToken($userToken);
        if (!is_object($user)){
            throw $this->createNotFoundException("Do not mess with me. Baby Hackerz");
        }
        
        $longitude = $request->query->get('longitude');
        $latitude = $request->query->get('latitude');
        
        $annonces = $this->getDoctrine()->getRepository('CovoiturageTransactionBundle:Annonce')->createQueryBuilder('a');        
        $maxDist = cos(1000 / 6371);
        $annonces
        /*->addSelect(
            '( 3959 * acos(cos(radians(' . $latitude . '))' .
                '* cos( radians( a.arrivee.latitude ) )' .
                '* cos( radians( a.arrivee.longitude )' .
                '- radians(' . $longitude . ') )' .
                '+ sin( radians(' . $latitude . ') )' .
                '* sin( radians( a.arrivee.latitude ) ) ) ) as distance'
        )*/
        /*->addSelect(
            '6371 * 2 * atan2(sqrt(sin((a.arrivee.latitude - '.$latitude.')* pi() / 360) * 
                sin((a.arrivee.latitude - '.$latitude.') * pi() / 360) + 
                cos($latitude) * cos(a.arrivee.latitude) * 
                sin((a.arrivee.longitude - '.$longitude.') * pi() / 360) *
                sin((a.arrivee.longitude - '.$longitude.') * pi() / 360)),
        sqrt(1 - (sin((a.arrivee.latitude - '.$latitude.')* pi() / 360) * 
                sin((a.arrivee.latitude - '.$latitude.') * pi() / 360) + 
                cos($latitude) * cos(a.arrivee.latitude) * 
                sin((a.arrivee.longitude - '.$longitude.') * pi() / 360) *
                sin((a.arrivee.longitude - '.$longitude.') * pi() / 360)))
                )'
        )*/
        ->addSelect("(cos(sin(RADIANS($latitude)) * "
                . "sin(RADIANS(a.arrivee.latitude)) + cos(RADIANS($latitude)) * "
                . "cos(RADIANS(a.arrivee.latitude)) * cos(RADIANS(a.arrivee.longitude)"
                . " - RADIANS($longitude)))) as Distance")
        ->andWhere('a.archivee = :archivee')
        ->setParameter('archivee', 0)
        ->having('distance < :distance')
        ->setParameter('distance', $maxDist)
        ->orderBy('distance', 'ASC');
        
        return $annonces->getQuery()->getResult();
    }
    /*
    public function getAnnoncesGeolocaliseesLessThan1KM($longitude, $latitude, $address){
       utilise le service pour trouver la localisatoin de l'address et ensuite computer si < à 1 km 
     * return $annonces;
    }*/
    
     /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Proposer une annonce.",
     *  input="Covoiturage\TransactionBundle\Form\AnnonceType",
     *  output="Covoiturage\TransactionBundle\Entity\Annonce",
     * )
     */
    public function postUserAnnonceAction(Request $request)
    {
        $userToken = $request->request->get('token');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository('CovoiturageUserBundle:Users')->findOneByToken($userToken);

        if(is_object($user)){
            $annonceEntity = new Annonce();
            $form = $this->createForm(new AnnonceType(), $annonceEntity);
            $date = new \DateTime($request->request->get('dateDepart'));            
            $form->bind(array(
                "prix" => $request->request->get('prix'),
                "recurrent" => ($request->request->get('recurrent') === '1' ? true : false),
                "depart" => $request->request->get('depart'),
                "arrivee" => $request->request->get('arrivee'),
                "voiture" => $request->request->get('voiture'),
                "nbPlacesDisponibles" => $request->request->get("nbPlacesDisponibles"),
            ));
            
            if ($form->isValid()){
                //Vérifier la date,
                $annonceEntity->setDateDepart($date);
                $minute = $annonceEntity->getDateDepart()->diff(new \DateTime());
                $minute = ($minute->d*24*60 + $minute->h*60+ $minute->i);
                if($minute < 30){
                    throw $this->createAccessDeniedException("Le départ de ce voyage doit être prévu au moins 30 min à l'avance");
                }
                $conducteur = $entityManager->getRepository('CovoiturageUserBundle:Conducteur')->findOneByUser($user);
                if(!is_object($conducteur)){
                    throw $this->createNotFoundException();
                }
                $annonceEntity->setConducteur($conducteur);
                $entityManager->persist($annonceEntity);
                $entityManager->flush();
                
                return $annonceEntity;
            }
            return array(
                'form' => $form,
            );
        }   
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Modifier une annonce publiée. Ne peut modifier si un voyage a été crée là dessus + Restriction de création d'annonce",
     *  requirements={
     *      {
     *          "name"="annonce",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de l'annonce dont on veut modifier les informations"
     *      }
     *  },
     * )
     */
    public function putAnnonceAction(Request $request, $annonce){
        // Vérifier que c'est bien l'annonce du user dont le token est fourni en paramètre
        // Pareil que post annonce, seule différence, on ne crée pas une nouvelle annonce mais on modifie celle qui existe
        // Si on est dans cette fonction, alors forcément le conducteur de l'annonce existe déjà, vu qu'il a publiée une annonce
        // et veut ensuite la modifier.
        // Ne peut modifier l'annonce si déjà un voyage a été crée dessus.
        // Est obligé de remplir une nouvelle fois tout les champs
        
        $userToken = $request->request->get('token');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository('CovoiturageUserBundle:Users')->findOneByToken($userToken);

        if(is_object($user)){
            $annonceEntity = $entityManager->getRepository('CovoiturageTransactionBundle:Annonce')->find($annonce);
            if (($annonceEntity->getConducteur()->getUser() != $user) || 
                    is_object($entityManager->getRepository('CovoiturageTransactionBundle:Voyage')->findByAnnonce($annonce))){
                throw $this->createAccessDeniedException("Access Denied NYGGA !!");
            }
            //Vérifier qu'aucun voyage n'a été crée
            $form = $this->createForm(new AnnonceType(), $annonceEntity);
            $date = new \DateTime($request->request->get('dateDepart'));            
            $form->bind(array(
                "prix" => $request->request->get('prix'),
                "recurrent" => ($request->request->get('recurrent') === '1' ? true : false),
                "depart" => $request->request->get('depart'),
                "arrivee" => $request->request->get('arrivee'),
                "voiture" => $request->request->get('voiture')
            ));
            
            if ($form->isValid()){
                //Vérifier la date,
                $annonceEntity->setDateDepart($date);
                $minute = $annonceEntity->getDateDepart()->diff(new \DateTime());
                $minute = ($minute->d*24*60 + $minute->h*60+ $minute->i);
                if($minute < 30){
                    throw $this->createAccessDeniedException("Le départ de ce voyage doit être prévu au moins 30 min à l'avance");
                }
                $entityManager->persist($annonceEntity);
                $entityManager->flush();
                
                return $annonceEntity;
            }
            return array(
                'form' => $form,
            );
        }   
    }
}

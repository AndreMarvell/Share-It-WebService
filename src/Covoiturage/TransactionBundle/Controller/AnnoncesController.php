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
    public function getAnnonceAction($id){

      $em   = $this->getDoctrine()->getManager();

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
     *      {
     *          "name"="driverId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du conducteur"
     *      }
     *  },
     * )
     */    
    public function getListeAnnoncesAction($driverId){
        // get the entity manager with repository that handle Annonce db connections
        // En gros on a déjà lié et spécifié que l'on traite la table annonce
        $annonceRepos = $this->getDoctrine()->getRepository('CovoiturageTransactionBundle:Annonce');
        $listeAnnonces = $annonceRepos->findByConducteur($driverId);
        
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
        
        $annonces = $this->getDoctrine()->getRepository('CovoiturageTransactionBundle'
                . ':Annonce')->createQueryBuilder('a');
        $annonces
        ->select('a')
        ->addSelect(
            '( 3959 * acos(cos(radians(' . $latitude . '))' .
                '* cos( radians( a.arrivee.latitude ) )' .
                '* cos( radians( a.arrivee.longitude )' .
                '- radians(' . $longitude . ') )' .
                '+ sin( radians(' . $latitude . ') )' .
                '* sin( radians( a.arrivee.latitude ) ) ) ) as distance'
        )
        ->andWhere('a.archivee = :archivee')
        ->setParameter('archivee', 1)
        ->having('distance < :distance')
        ->setParameter('distance', 1000)
        ->orderBy('distance', 'ASC');
        
        return $annonces;
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
     *  description="Modifier une annonce publiée",
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
    public function putAnnonceAction(Request $request, $demandeId){
        
    }
}

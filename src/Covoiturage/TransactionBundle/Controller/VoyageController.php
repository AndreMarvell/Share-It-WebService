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
        
        $user = $em->getRepository("CovoiturageUserBundle:Users")->findOneByToken($request->query->get('token'));
        $isDriver = $request->query->get('isConducteur');
        if(!is_object($userToken) || empty($isDriver)){
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
    public function getListe_voyagesAction(Request $request){
        
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository("CovoiturageUserBundle:Users")->findOneByToken($request->query->get('token'));
        if (!is_object($user)){
            throw $this->createNotFoundException ("I\'m smartter than ya!! Nygga");
        }else{
            // There is surely a better way of doing this, Just imagine if we have 1200000 users...
            // Trouver tout les voyages en tant que passager ou en tant que conducteur. En tant que passager, on est voyageur. Mais le
            // Voyageur n'est pas un conducteur d'où l'utilisation de l'annonce pour trouver le conducteur.
            // 
            // En tant que Passager
            //$voyages = $entityManager->getRepository("CovoiturageTransactionBundle:Voyage")->find
            $qbVoyageurs = $this->getDoctrine()->getRepository('CovoiturageUserBundle:Voyageur')->createQueryBuilder('v');
            /*$voyages*/$travellers = $qbVoyageurs->join('v.passager', 'p')
                        ->addSelect('p')
                        ->where('p = :passager')
                        ->setParameter('passager', $entityManager
                                                    ->getRepository("CovoiturageUserBundle:Passager")
                                                    ->findOneByUser($user))
                        ->getQuery()
                        ->getResult();
            
            $voyages = $entityManager->getRepository("CovoiturageTransactionBundle:Voyage")->findByVoyageurs($travellers[0]);
            
            /*$qbVoyage = $this->getDoctrine()->getRepository('CovoiturageTransactionBundle:Voyage')->createQueryBuilder('v');
            $voyage = $qbVoyage->join('v.voyageurs', 'v')
                        ->addSelect('v')
                        ->addSelect('p')
                        ->where('v.passager = p')
                        ->addWhere("p = :passager")
                        ->setParameter('passager', $entityManager
                                                    ->getRepository("CovoiturageUserBundle:Passager")
                                                    ->findOneByUser($user))
                        ->getQuery()
                        ->getResult();*/ // Récupération de la demande déjà faite par un passager.
                    
        }
        return $voyages /*$travellers*/;
    }
    
    ////////////////////////////////////Tester cette fonction ci-dessous
    /*
     * /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Craation d un voyage lorsqu un passager et le conducteur sont tombes d'accord",
     *  requirements={
     *      {
     *          "name"="demande",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de la demande validée"
     *      },
     *      {
     *          "name"="prixFinal",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="prix final du voyage"
     *      },
     *  },
     *  output="Covoiturage\TransactionBundle\Entity\Voyage",
     * )
     */   
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Création d un voyage lorsqu un passager et le conducteur sont tombes d'accord",
     *  input="Covoiturage\TransactionBundle\Form\VoyageType",
     *  output="Covoiturage\TransactionBundle\Entity\Voyage",
     * )
     */   
    public function postVoyageAction(Request $request/*, $demande, $prixFinal*/){
        // Si le voyage existe déjà, alors rajouter juste le prix du coté voyageur
        // Demande pour set le prix et récupérer annonce, qui récupère voiture qui réduit le nombre de places disponible pour ce voyage
        // Non rajouter un attribut nb_place_pour_voyage car cela peut diminuer le nb de place véritable de la voiture alors qu'il s'agit
        // Juste du nombre de place dispo pour un voyage en particulier
        // Du coup pour demande, le prix de la demande sera le prix final
        
        // Seul le conducteur ayant posté l'annonce peut valider une demande et donc créer un voyage
        // De ce fait, son token doit correspondre à celui du conducteur de l'annonce (Pour éviter qu'un conducteur ne valide la demande d'une autre
        // annonce qui ne le concerne pas)
        
        //$userToken = $request->query->get("token"); // Si les paramètres sont passés dans l'url
        
         // Si les paramètres sont passés en post ou en get.
        $demande = $request->request->get("demande"); // Peut pas faire de bind avec l'entité voyage, vu que certains champs n'existent pas encore
        $prixFinal = $request->request->get("prixFinal");
        $userToken = $request->request->get("token");
        
        if ((preg_match("/^[0-9]+$/", $prixFinal) == 0) ||
                (preg_match("/^[0-9]+$/", $demande) == 0)){
            throw $this->createAccessDeniedException("Who do you think you are ?");
        }
        // On récupère le conducteur qui a envoyé son token et con regarde s'il s'agit en effet du conducteur de l'annonce.
        $qb = $this->getDoctrine()->getRepository('CovoiturageUserBundle:Conducteur')->createQueryBuilder('c');
        $driver = $qb
                    ->join('c.user', 'u')
                    ->addSelect('u')
                    ->where('u.token = :token')
                    ->setParameter('token', $userToken)
                    ->getQuery()
                    ->getResult()
        ;
        $entityManager = $this->getDoctrine()->getManager();
        $demandeEntity = $entityManager->getRepository("CovoiturageTransactionBundle:Demande")->find($demande);
        if (!is_object($demandeEntity)){
            throw $this->createAccessDeniedException("Who do you think you are ?");
        }
        if ($driver != $demandeEntity->getAnnonce()->getConducteur()){
            throw $this->createAccessDeniedException("Who do you think you are ?");
        }
        $nb_places_validees = 1; // Un user peut reserver pour plusieurs personnes.
        // C'est le bon conducteur qui a validé, alors créer le voyage ou mettre à jour les paramètres
        $demandeEntity->getAnnonce()->setNbPlacesDisponibles($demandeEntity->getAnnonce()->getNbPlacesDisponibles() - $nb_places_validees); ///////////// Réduire le nombre de places du voyage
        // Créer le voyageur
        $voyageur = new \Covoiturage\UserBundle\Entity\Voyageur();
        $voyageur->setPrixFinal($prixFinal);
        $voyageur->setPassager($demandeEntity->getPassager());
        $voyageur->setCodePassager($this->generateRandomString());
        
        // Créer le voyage
        $voyage = $entityManager->getRepository("CovoiturageTransactionBundle:Voyage")->findByAnnonce($demandeEntity->getAnnonce());
        if (!is_object($voyage)){
            $voyage = new Voyage();
            $voyage->setAnnonce($demandeEntity->getAnnonce());
        }
        // Ne pas ajouter le même voyageur deux fois de suite pour la même annonce
        foreach($voyage->getVoyageurs() as $v){
            if($v->getPassager() == $voyageur->getPassager()){
                throw $this->createAccessDeniedException("Can not the same person two times");
            }
        }
        
        $voyage->addVoyageur($voyageur);
        $voyage->setCodeConducteur($this->generateRandomString());
        
        $entityManager->persist($voyageur);
        $entityManager->persist($voyage);
        $entityManager->flush();
        
        return $voyage;
    }
    /*
     * Function de génération d'une chaine aléartoire qui représentera les codes passagers et conducteurs
     */
    function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Valider le code conducteur par un passager.",
     *  requirements={
     *      {
     *          "name"="Voyage Id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du voyage"
     *      },
     *  },
     * )
     */
    public function putValidateCodeConducteurAction(Request $request, $voyageId){
        // On récupère le voyageur dont le token correspond à celui envoyé dans request et on valide le code conducteur.
        // On a besoin du token, de l'id du voyage, et du code conducteur.
        
        $entityManager = $this->getDoctrine()->getManager();
        $voyage = $entityManager->getRepository("CovoiturageTransactionBundle:Voyage")->find($voyageId);
        if (!is_object($voyage)){
            throw $this->createAccessDeniedException("Please call the 911 and ask them how to hack a site. LOL !");
        }
        
        foreach ($voyage->getVoyageurs() as $v){
            if($v->getPassager()->getUser()->getToken() != $request->query->get("token")){
                // Nothing
            }else{
                $voyageur = $v;
            }
        }
        if (!is_object($voyageur)){ // On vérifie que le token donné fait bien référence à un voyageur existant dans ce voyage donné en identifiants.
            // Seul un passager peut valider le code conducteur.
            throw $this->createAccessDeniedException("Please call the 911 and ask them how to hack a site. LOL !");
        }
        
        $codeConducteur = $request->query->get("codeConducteur");
        
        if ($voyage->getCodeConducteur() != $codeConduteur){
            throw $this->createAccessDeniedException("Pas valide");
        }
        
        $voyageur->setConducteurValide(true);
        $entityManager->persist($voyageur);
        $entityManager->flush();
        
        return $voyage;
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Valider le code passager par le conducteur.",
     *  requirements={
     *      {
     *          "name"="Voyage Id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du voyage"
     *      },
     *  },
     * )
     */
    public function putValidateCodePassagerAction(Request $request, $voyageId){
        // On récupère le voyage et on vérifie bien que le token correspond à celui du conducteur
        // Pour éviter que n'importe qui ne puisse valider le code passager.
        // On a toujours besoin du token, de l'id du voyage et du code passager
        
       $voyage = $entityManager->getRepository("CovoiturageTransactionBundle:Voyage")->find($voyageId);
       if (!is_object($voyage)){
            throw $this->createAccessDeniedException("Please call the 911 and ask them how to hack a site. LOL !");
       }
       if ($voyage->getAnnonce()->getConducteur()->getUser()->getToken() != $request->query->get("token")){
           // On vérifie que le token est bien celui du conducteur
           throw $this->createAccessDeniedException("Please call the 911 and ask them how to hack a site. LOL !");
       }
       // On cherche dans l'ensemble des passagers s'il y a un dont le code passager correspond
       foreach($voyage->getVoyageurs() as $v){
           if ($v->getCodePassager() === $request->query->get("codePassager")){
               $voyageur = $v;
               $voyageur->setPassagerValide(true);
               $entityManager->persist($voyageur);
           }
       }
       $entityManager->flush();
       return $voyage;
    }
}

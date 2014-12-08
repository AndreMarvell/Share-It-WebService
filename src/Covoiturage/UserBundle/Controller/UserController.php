<?php

namespace Covoiturage\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\RedirectView;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use Covoiturage\UserBundle\Entity\Users;
use Covoiturage\UserBundle\Form\UsersInscriptionFBType;
use Covoiturage\UserBundle\Form\UsersInscriptionType;
use Covoiturage\UserBundle\Form\UsersType;
use Covoiturage\UserBundle\Form\VoitureType;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends \FOS\RestBundle\Controller\FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récuperer un user par son identifiant",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de l'utilisateur"
     *      }
     *  },
     * )
     */
    public function getUserAction($id){

      $em   = $this->getDoctrine()->getManager();

      $user =   $em->getRepository('CovoiturageUserBundle:Users')->findOneById($id);

      if(!is_object($user)){
        throw $this->createNotFoundException();
      }else{
        $token = $this->generateToken();
        $user->setToken($token);
      }
      

      return $user;
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Inscription d'un utilisateur par facebook",
     *  input="Covoiturage\UserBundle\Form\UsersInscriptionFBType",
     *  output="Covoiturage\UserBundle\Entity\Users",
     * )
     */
    public function postUserFacebookAction(Request $request)
    {
        $entity = new Users();

        $token = $this->generateToken();
        $entity->setToken($token);

        $form = $this->createForm(new UsersInscriptionFBType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user =   $em->getRepository('CovoiturageUserBundle:Users')->findOneByFacebookId($entity->getFacebookId());

            if(is_object($user)){
                return $user;
            }else{

                $user =   $em->getRepository('CovoiturageUserBundle:Users')->findOneByEmail($entity->getEmail());

                if(is_object($user)){
                   return $user; 
                }else{
                    $em->persist($entity);
                    $em->flush();

                    return $this->redirectView(
                        $this->generateUrl(
                            'api_get_user',
                            array('id' => $entity->getId())
                            ),
                        Codes::HTTP_FOUND
                        );
                }
            }
        }
        return array(
            'form' => $form,
        );
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Inscription d'un utilisateur",
     *  input="Covoiturage\UserBundle\Form\UsersInscriptionType",
     *  output="Covoiturage\UserBundle\Entity\Users",
     * )
     */
    public function postUserInscriptionAction(Request $request)
    {
        $entity = new Users();

        $token = $this->generateToken();
        $entity->setToken($token);

        $form = $this->createForm(new UsersInscriptionType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user =   $em->getRepository('CovoiturageUserBundle:Users')->findOneByEmail($entity->getEmail());

            if(is_object($user)){
                throw $this->createNotFoundException("Ce mail est déjà utilisé!");
            }else{
                
                $entity->setSalt(md5(time()));
                $encoder = new MessageDigestPasswordEncoder('sha1');
                $password = $encoder->encodePassword($form->get('password')->getData(), $entity->getSalt());
                
                $entity->setPassword($password);
                
                $em->persist($entity);
                $em->flush();

                return $this->redirectView(
                    $this->generateUrl(
                        'api_get_user',
                        array('id' => $entity->getId())
                        ),
                    Codes::HTTP_FOUND
                    );
            }
        }
        return array(
            'form' => $form,
        );
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Connexion d'un utilisateur",
     *  input="Covoiturage\UserBundle\Form\UsersInscriptionType",
     *  output="Covoiturage\UserBundle\Entity\Users",
     * )
     */
    public function postUserConnexionAction(Request $request)
    {
        $entity = new Users();

        

        $form = $this->createForm(new UsersInscriptionType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user =   $em->getRepository('CovoiturageUserBundle:Users')->findOneByEmail($entity->getEmail());

            $encoder = new MessageDigestPasswordEncoder('sha1');
            $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
                
            if(is_object($user) && $password==$user->getPassword()){
                
                // On genere le token
                $token = $this->generateToken();
                $user->setToken($token);
                // On enregistre le token
                $em->persist($user);
                $em->flush();
                // On renvoi l'objet User
                return $user;
            }else{
                throw $this->createNotFoundException("Désolé la tentative de connexion a échoué");
            }
        }
        return array(
            'form' => $form,
        );
    }
    
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Mise a jour d'un utilisateur",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant de l'utilisateur"
     *      }
     *  },
     *  input="Covoiturage\UserBundle\Form\UsersType",
     *  output="Covoiturage\UserBundle\Entity\Users",
     * )
     */
    public function putUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CovoiturageUserBundle:Users')->findOneById($id);
        
        if(!is_object($entity)){
            throw $this->createNotFoundException();
        }else{
            $geocoding = $this->container->get('geocoding');
            $form = $this->createForm(new UsersType(), $entity, array("em"=>$em, "geocoding"=>$geocoding));
            $form->bind($request);

            if ($form->isValid()) {

                $em->persist($entity);
                $em->flush();

                return $this->view(null, Codes::HTTP_NO_CONTENT);
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
     *  description="Ajout d'une voiture à conducteur",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Identifiant du conducteur"
     *      }
     *  },
     *  input="Covoiturage\UserBundle\Form\VoitureType",
     *  output="Covoiturage\UserBundle\Entity\Conducteur",
     * )
     */
    public function postVoitureAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $conducteur = $em->getRepository('CovoiturageUserBundle:Conducteur')->findOneById($id);
        
        if(!is_object($conducteur)){
            throw $this->createNotFoundException();
        }else{
            $entity = new \Covoiturage\UserBundle\Entity\Voiture();
            $form = $this->createForm(new VoitureType(), $entity);
            $form->bind($request);
                    
            if ($form->isValid()) {
                $conducteur->addVoiture($entity);
                $em->persist($conducteur);
                $em->flush();

                return $this->view(null, Codes::HTTP_NO_CONTENT);
            }

            return array(
                'form' => $form,
            );
        }
    }
    
    
    public function generateToken() {
//        $csrf = $this->get('form.csrf_provider'); 
//        $token = $csrf->generateCsrfToken('unknow');
        $token = md5(uniqid(mt_rand(), true));
        return $token;
    }
}

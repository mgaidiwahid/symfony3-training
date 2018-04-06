<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
  public function indexAction($page)
  {
    if ($page < 1) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }
    $nbPerPage = 3;
    $em = $this->getDoctrine()
      ->getManager();
    $listAdverts=  $em->getRepository('OCPlatformBundle:Advert')->getAdverts($page,$nbPerPage);
    
    $nbPages = ceil(count($listAdverts) / $nbPerPage);
    if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }   
      return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts,
      'nbPages'     => $nbPages,
      'page'        => $page
    ));
  }

  public function viewAction($id)
  {
    $repository = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert');
    
    $advert = $repository->find($id);
    
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    $listApplications = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('OCPlatformBundle:Application')
                             ->findBy(array('advert' => $advert));
    $listAdvertSkills = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('OCPlatformBundle:AdvertSkill')
                             ->findBy(array('advert' => $advert));

    
    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert,
      'listApplications' => $listApplications,
      'listAdvertSkills' => $listAdvertSkills
    ));
  }

  public function addAction(Request $request)
  {
    
    $em = $this->getDoctrine()->getManager(); 

    // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
    if ($request->isMethod('POST')) {

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
    }

    return $this->render('OCPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {

    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
    
    if (null === $advert) {
         throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
   }
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
      return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));

    }
    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(

      'advert' => $advert

    ));
  }

  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    foreach ($advert->getCategories() as $category) {
      $advert->removeCategory($category);
    }
    $em->flush();

    return $this->render('OCPlatformBundle:Advert:delete.html.twig');
  }
  public function menuAction($limit)
  {
    $em = $this->getDoctrine()->getManager();
    $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }  
}
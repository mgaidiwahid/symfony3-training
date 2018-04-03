<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
  public function indexAction($page)
  {
      $listAdverts = array(
      array(
        'title'   => 'Recherche développpeur Symfony',
        'id'      => 1,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Mission de webmaster',
        'id'      => 2,
        'author'  => 'Hugo',
        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'author'  => 'Mathieu',
        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
        'date'    => new \Datetime())
    );
      return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts
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
      'listAdvertSkills' => $listAdvertSkills,  
    ));
  }

  public function addAction(Request $request)
  {
    $advert = new Advert();
    $advert->setTitle('Recherche développeur drupal 2018.');
    $advert->setAuthor('Alexandre');
    $advert->setContent("Nous recherchons un développeur drupal débutant sur Paris…");
    


    $application1 = new Application();
    $application1->setAuthor('Marine');
    $application1->setContent("J'ai toutes les qualités requises.");

    $application2 = new Application();
    $application2->setAuthor('Pierre');
    $application2->setContent("Je suis très motivé.");

    $application1->setAdvert($advert);
    $application2->setAdvert($advert);
    
    $em = $this->getDoctrine()->getManager();
    $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();
    
    foreach ($listSkills as $skill) {
      $advertSkill = new AdvertSkill();
      $advertSkill->setAdvert($advert);
      $advertSkill->setSkill($skill);
      $advertSkill->setLevel('Expert');
      $em->persist($advertSkill);

    }    
    $em->persist($advert); 
    $em->persist($application1);
    $em->persist($application2);
    
    $em->flush();    

    // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
    if ($request->isMethod('POST')) {

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
    }

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
  }

  public function editAction($id, Request $request)
  {

    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
    
    if (null === $advert) {
         throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
   }
    $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();  
    foreach ($listCategories as $category) {
      $advert->addCategory($category);
    }    
    $em->flush();
    
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

    return $this->redirectToRoute('oc_platform_home');       
    /*return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
      'advert' => $id
    ));*/
  }
  public function menuAction($limit)
  {

    $listAdverts = array(
      array('id' => 2, 'title' => 'Recherche développeur Symfony'),
      array('id' => 5, 'title' => 'Mission de webmaster'),
      array('id' => 9, 'title' => 'Offre de stage webdesigner')
    );

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }  
}
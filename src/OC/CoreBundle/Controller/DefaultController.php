<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
      $listAdverts = array(
      array(
        'title'   => 'Chef de Projet Système Approfondi ERP - BTP H/F',
        'id'      => 1,
        'author'  => 'Groupe Marc',
        'content' => 'Sous l\'autorité du Responsable administratif et financier, nous vous confions la mise en place…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Développeur Applications Mobiles H/F',
        'id'      => 2,
        'author'  => 'Manpower',
        'content' => 'Manpower NANTES INFORMATIQUE recherche pour son client, un acteur du secteur …',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Développeur Full Stack Web H/F',
        'id'      => 3,
        'author'  => 'Roullier',
        'content' => 'Notre ambition ? Innover. Notre mission ? Relever les défis nutritionnels de demain…',
        'date'    => new \Datetime())
    );
      
      return $this->render('OCCoreBundle:Default:index.html.twig', array(
      'listAdverts' => $listAdverts
    ));

    }
    public function viewAction($id)
  {
    $advert = array(
      'title'   => 'Chef de Projet Système Approfondi ERP - BTP H/F',
      'id'      => $id,
      'author'  => 'Groupe Marc',
      'content' => 'Sous l\'autorité du Responsable administratif et financier, nous vous confions la mise en place…',
      'date'    => new \Datetime()
    );

    return $this->render('OCCoreBundle:Default:view.html.twig', array(
      'advert' => $advert
    ));
  }
    public function contactAction()
  {
    $this->addFlash('notice', 'La page de contact n’est pas encore disponible');
    return $this->render('OCCoreBundle:Default:contact.html.twig');
    //return $this->redirectToRoute('oc_core_homepage');
   
  }
}

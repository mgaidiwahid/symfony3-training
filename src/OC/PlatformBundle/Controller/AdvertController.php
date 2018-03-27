<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $content = $this->get('templating')
                        ->render('OCPlatformBundle:Advert:index.html.twig', array('nom' => 'winzou'));
        return new Response($content);
    }
    public function quitAction()
    {
        $content = $this->get('templating')
                        ->render('OCPlatformBundle:Advert:quit.html.twig', array('prenom' => 'admin'));
        return new Response($content);
    }    
}

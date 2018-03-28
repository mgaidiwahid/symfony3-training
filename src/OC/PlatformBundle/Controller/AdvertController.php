<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $url = $this->get('router')->generate(
            'oc_platform_view', // 1er argument : le nom de la route
            array('id' => 5),    // 2e argument : les valeurs des paramètres
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        return new Response("L'URL de l'annonce d'id 5 est : ".$url);
    }
    public function viewAction($id)
    {
        return new Response("Affichage de l'annonce d'id : ".$id);
    }   
    public function viewSlugAction($slug, $year, $_format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$_format."."
        );
    }      
}

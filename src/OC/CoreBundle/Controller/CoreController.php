<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectReponse;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller{
    public function indexAction(){
        return $this->render ('OCCoreBundle:Core:index.html.twig');
    }
    public function contatcAction(Request $request){
        $session=$request->getSession();
        $session->getFlashBag()->add('info', "La page de contact n'est pas encore disponible.");
        return $this->redirectToRoute('oc_core_home');
    }
}
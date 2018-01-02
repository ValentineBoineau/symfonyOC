<?php


namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\OCPlatformBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page){
        if ($page<1){
            throw new NotFoundHttpException('Page"'.$page.'" inexistante.');
        }
        return $this->render('OCPlatformBundle:Advert:index.html.twig');
    }

    public function viewAction($id){
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('id'=>$id));
    }

    public function addAction(Request $request){
        if ($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée');
            return $this->redirectToRoute('oc_platform_view', array('id'=>5));
        }
        return $this->render('OCPlatformBundle:Advert:add.html.twig');

    }

    public function editAction($id, Request $request){
        if ($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce B=bien modifiée');
            return $this->redirectToRoute('oc_platform_view', array('id'=>5));
        }
        return $this->render('OCPlatformBundle:Advert:edit.html.twig');
    }

    public function deleteAction($id){
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');

    }

    public function menuAction(){
        $listAdverts=array(
            array('id'=>2, 'title'=>'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts'=>$listAdverts));
    }
}

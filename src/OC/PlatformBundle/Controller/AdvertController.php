<?php


namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\OCPlatformBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{
    public function indexAction($page){
        if($page<1){
            throw $this->createNotFoundException("Page ".page." inexistante.");
        }
        $nbPerPage=3;
        $listAdverts=$this->getDoctrine()
            ->getManager()
            ->getRepository("OCPlatformBundle:Advert")
            ->getAdverts($page,$nbPerPage);
        $nbPages=ceil(count($listAdverts)/$nbPerPage);
        if($page>$nbPages){
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        return $this->render('OCPlatformBundle:Advert:index.html.twig',array(
            'listAdverts'=>$listAdverts,
            'nbPages'=>$nbPages,
            'page'=>$page
        ));
    }

    public function viewAction(Advert $advert){
        $em=$this->getDoctrine()->getManager();
        $listAdvertSkill=$em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(array('advert'=>$advert));
        $listApplications=$em->getRepository('OCPlatformBundle:Application')->findBy(array('advert'=>$advert));
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert'=>$advert,'listApplications'=>$listApplications,'listAdvertSkill'=>$listAdvertSkill));
    }

    public function addAction(Request $request){

        $em=$this->getDoctrine()->getManager();
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée.');
            return $this->redirectToRoute('oc_platform_viw', array('id'=>$advert->getId()));
        }

    }

    public function editAction(Advert $advert, Request $request){
        $em =$this->getDoctrine()->getManager();
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien modofiée.');
            return $this->redirectToRoute('oc_platform_view', array('id'=>$advert->getId()));
        }
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array('advert'=>$advert->getId()));
    }

    public function deleteAction(Advert $advert){
        $em=$this->getDoctrine()->getManager();

        foreach ($advert->getCategories() as $category){
            $advert->removeCategory($category);
        }
    $em->flush();
    }

    public function menuAction($limit){
        $em=$this->getDoctrine()->getManager();

        $listAdverts=$em->getRepository('OCPlatformBundle:Advert')->findBy(array(array('date'=>desc),$limit,0));
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts'=>$listAdverts));
    }
}

<?php


namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{
    public function indexAction($page){
        $mailer=$this->container->get('mailer');
    }

    public function viewAction($id){
        $repository=$this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Advert');
        $advert=$repository->find($id);

        if (null===$advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert'=>$advert,));
    }

    public function addAction(Request $request){
        $advert=new Advert();
        $advert->setTitle('Recherche développeur Sympfony');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un dev symfony débutant sur Lyon. Blablabla...");

        $application1=new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        $application2=new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $image =new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        $advert->setImage($image);

        $em=$this->getDoctrine()->getManager();

        $em->persist($advert);
        $em->persist($application1);
        $em->persist($application2);

        $em->flush();

        if ($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée');
            return $this->redirectToRoute('oc_platform_view', array('id'=> $advert->getId()));
        }
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert'=>$advert));

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

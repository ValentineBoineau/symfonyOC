<?php


namespace OC\PlatformBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use OC\PlatformBundle\Entity\Category;
use OC\PlatformBundle\Form\AdvertEditType;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class AdvertController extends Controller
{

    public function indexAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
         $nbPerPage = 3;
        // On récupère notre objet Paginator
        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->getAdverts($page, $nbPerPage)
        ;
        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts) / $nbPerPage);
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue

        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
            'nbPages'     => $nbPages,
            'page'        => $page,
        ));
    }

    public function viewAction(Advert $advert){
        $em=$this->getDoctrine()->getManager();
        $listAdvertSkills=$em->getRepository('OCPlatformBundle:AdvertSkill')
            ->findBy(array('advert'=>$advert));
        if($listAdvertSkills===null){
            $listAdvertSkills=new ArrayCollection();
        }
        $listApplications=$em->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert'=>$advert));
        if($listApplications===null){
            $listApplications=new ArrayCollection();
        }
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'=>$advert,
            'listApplications'=>$listApplications,
            'listAdvertSkills'=>$listAdvertSkills));
    }



  public function addAction(Request $request)
    {
        $advert = new Advert();
        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    public function editAction(Advert $advert, Request $request){
        $em =$this->getDoctrine()->getManager();
        $form=$this->get('form.factory')->create(AdvertEditType::class,$advert);
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien modofiée.');
            return $this->redirectToRoute('oc_platform_view', array('id'=>$advert->getId()));
        }
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert'=>$advert->getId(),
            'form'=>$form->createView()));
    }

    public function deleteAction(Advert $advert, Request $request){
        $em=$this->getDoctrine()->getManager();

        $form=$this->get('form.factory')->create();
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em->remove($advert);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info',"L'annonce a bien été supprimée.");
            return $this->redirectToRoute('oc_platform_home');
        }

        foreach ($advert->getCategories() as $category){
            $advert->removeCategory($category);
        }
        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
            'advert'=>$advert,
            'form'=>$form->createView(),
        ));
    }

    public function menuAction($limit){
        $em=$this->getDoctrine()->getManager();

        $listAdverts=$em->getRepository('OCPlatformBundle:Advert')->findBy(
            array(),
            array('date'=>'desc'),
            $limit
        ,0);
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts'=>$listAdverts));
    }
}

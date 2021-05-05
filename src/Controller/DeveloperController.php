<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Repository\DeveloperRepository;
use App\Repository\ProjectRepository;
use App\Form\DeveloperEditIntroFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DeveloperController extends AbstractController
{
    private $twig;
    private $entityManager;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/developer", name="developer_index")
     */
    public function index( DeveloperRepository $developerRepository)
    {


        return new Response($this->twig->render('developer/index.html.twig',[
            'developers' => $developerRepository->findAll(),
        ]));
        
    }

    /**
     *  @Route("/developer/{slug}", name="developer")
     */
    public function show(Developer $developer, DeveloperRepository $developerRepository)
    {   
        $user = $this->getUser();
        if($user->getUserId()==$developer->getId()){
            $editIntroURL = $developer->getSlug().'/edit/intro';
            $edit = 'Edit';
        }
        

        return new Response($this->twig->render('developer/show.html.twig',[
            'developer' => $developer,
            'editIntroURL' => $editIntroURL,
            'edit' => $edit
        ]));
    }

    /**
     *  @Route("/developer/{slug}/edit/intro", name="developer_edit_intro")
     */
    public function developerEditIntro(Request $request,Developer $developer, DeveloperRepository $developerRepository)
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
        $user = $this->getUser();
        $developerId = $user->getUserId();
        $editDeveloperAccount =  $developerRepository->find($developerId);
        $form = $this->createForm(DeveloperEditIntroFormType::class, $editDeveloperAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($editDeveloperAccount);
            $this->entityManager->flush();
            return $this->redirectToRoute('developer',['slug' =>$editDeveloperAccount->getSlug()]);
        }

        return $this->render('developer/developerEditIntro.html.twig', [
            'create_form' => $form->createView(),
        ]);

    }

  

}

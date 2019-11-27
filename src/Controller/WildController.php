<?php

//src/Controller/WildController.php
namespace App\Controller;


use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{

    /**
     * Show all rows from Program's entity
     */
    /**
     * @Route("/wild", name="wild_index")
     * @return Response A response instancce
     */
    public function index() : Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if(!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table'
            );
        }
        return $this ->render('wild/index.html.twig',
            ['programs' => $programs,
        ]);
    }


    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/wild/show/{slug}", name="wild_show", defaults={"slug" = ""},
     * requirements={"slug" = "[a-z0-9\-]+"})
     * @return Response
     */
    public function show(string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug=ucwords(str_replace('-', ' ',$slug));

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }

    /**
     * Récuperer données liées à une catégorie donnée
     *
     * @Route("/wild/category/{categoryName}", name="show_category", defaults={"categoryName" = ""},
     * requirements={"categoryName" = "[a-z0-9\-]+"})
     * @return Response
     */
    public function showByCategory(string $categoryName) : Response
    {
        if(!$categoryName) {
            throw $this
            ->createNotFoundException('No category\'s name has been sent to find programs in programs table');
        }
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' =>$categoryName]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' =>$category], ['id' =>'DESC'], 3 );

        return $this->render('wild/category.html.twig',
            [
                'programs'=>$programs,
                'category'=>$category,
            ]);


    }

}

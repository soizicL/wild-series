<?php

//src/Controller/WildController.php
namespace App\Controller;


use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
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
     * @return Response A response instance
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

        $categoryName=ucwords(str_replace('-', ' ',$categoryName));

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' =>$categoryName]);


        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' =>$category], ['id' =>'DESC'], 3 );

        if(!$programs){
            throw $this->createNotFoundException('No program with ' .$categoryName. 'category, found in Program\'s table');
        }

        return $this->render('wild/category.html.twig',
            [
                'programs'=>$programs,
                'category'=>$category,
            ]);
    }

    /**
     * @Route("/wild/program/{id}", name="show_program", defaults={"programName" = null})
     * @return Response
     */
    public function showByProgram(?int $id) : Response
    {
        if(!$id) {
            throw $this
                ->createNotFoundException('No progranName has been sent to find programs in programs table');
        }

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' =>$program], ['id' => 'ASC']);

        if(!$seasons) {
            throw $this->createNotFoundException('No season with '.$id. ' program, found in Season\'s table');
        }


        return $this->render('wild/program.html.twig',
            [
                'program'=>$program,
                'seasons'=>$seasons,
            ]);
    }

    /**
     * Récuperer la liste des epidoses liées à une saison donnée, d'un program donné
     *
     * @Route("/wild/season/{id}", name="show_season", defaults={"id" = null})
     * @return Response
     */
    public function showBySeason(int $id) : Response
    {

        if (!$id) {
            throw $this->createNotFoundException('No season has been sent to find an episode in season\'s table');
        }
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);

        $program = $seasons->getProgram();
        $episodes = $seasons->getEpisodes();

        return $this->render('wild/season.html.twig', [
            'episodes' => $episodes,
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * @param Episode $episode
     * @return Response
     * @Route("wild/episode/{id}", name="show_episode")
     */
    public function showEpisode(Episode $episode):Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'program' => $program,
            'season' => $season,
        ]);
    }


}

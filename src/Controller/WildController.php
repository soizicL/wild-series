<?php
// src/Controller/WildController.php
namespace App\Controller;
use App\Entity\Actor;
use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use App\Form\ProgramSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(Request $request): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        $form = $this->createForm(
            ProgramSearchType::class,
            null);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = implode($form->getData());
            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findBy([
                    'title' => mb_strtolower($data)
                ]);
        }
        $category = new Category();
        $formname = $this->createForm(CategoryType::class, $category);
        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs,
                'form' => $form->createView(),
                'formname' => $formname->createView(),
            ]
        );
    }


    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug}", name="show", defaults={"slug" = ""},
     * requirements={"slug" = "[a-z0-9\-]+"})
     * @return Response
     */
   public function show(string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = ucwords(str_replace('-', ' ', $slug));
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/category/{categoryName}", requirements={"categoryName"="[a-z0-9-]+"},
     *     defaults={"categoryName"=null},
     *     name="category")
     * @param string|null $categoryName
     * @return Response
     */
    public function showByCategory(?string $categoryName): response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category has been sent to find a category in category table');
        }
        $categoryName = preg_replace(
            '/-/', ' ', ucwords(trim(strip_tags($categoryName)), "-")
        );
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy([
                'name' => mb_strtolower($categoryName)
            ]);
        if (!$category) {
            throw $this
                ->createNotFoundException('No category with' . $categoryName . 'name found in category table');
        }
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );
        if (!$program) {
            throw $this
                ->createNotFoundException('No program found in program table');
        }
        return $this->render('wild/category.html.twig', [
            'categoryname' => ucwords(str_replace("-", " ", $categoryName)),
            'category' => $category,
            'programs' => $program
        ]);
    }

    /**
     * @Route("/show/{slug<^[ a-zA-Z0-9-é]+$>}", defaults={"slug" = null}, name="show")
     * @param string $slug
     * @return Response
     */
    public function showByProgram(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(
                ['program' => $program],
                ['id' => 'ASC']
            );
        if (!$season) {
            throw $this->createNotFoundException(
                'No season found in season\'s table.'
            );
        }
        return $this->render('wild/show.html.twig', [
            'season' => $season,
            'programs' => $program,
            'slug' => $slug,
        ]);
    }


    /**
     * Getting a program episodes by season
     *
     * @param int $id
     * @Route("/season/{id<^[ a-zA-Z0-9-é]+$>}}", name="season")
     * @return Response
     */
    public function showBySeason(int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No id has been sent to find a season in season\'s table.');
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);

        $program = $season->getProgram();
        if (!$season) {
            throw $this->createNotFoundException(
                'No season with ' . $id . ' found in season\'s table.'
            );
        }
        $episode = $season->getEpisodes();
        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode found in season\'s table.'
            );
        }
        return $this->render('wild/season.html.twig', [
            'programs' => $program,
            'episodes' => $episode,
            'season' => $season,
            'id' => $id
        ]);
    }

    /**
     * Getting a program  by episode
     *
     * @param int $id
     * @Route("/show_episode/{id}", name="episode")
     * @return Response
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(
                ['id' => 'ASC']
            );

        $season = $episode->getSeason();
        $program = $season->getProgram();
        return $this->render('wild/episode.html.twig', [
            'program' => $program,
            'episodes' => $episode,
            'season' => $season,
        ]);
    }

    /**
     * Getting an actor with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/actor/{name}", name="actor")
     * @return Response
     */
    public function actorName(?string $name): Response
    {

        if(!$name) {
            throw $this->createNotFoundException(
            'No slug has been sent to found a program in program\'stable.');
        }

        $name = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($name)), "-")
        );

        $actor = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['name'=>mb_strtolower($name)]);

        if(!$actor) {
            throw $this->createNotFoundException(
                'No actor with ' .$name. ' name, found in actor\'s table');
        }

        $program = $actor->getPrograms();

        if(!$program) {
            throw $this->createNotFoundException(
              'No program found in program\'s table.'
            );
        }
        return $this->render('wild/actor.html.twig', [
            'actors' => $actor,
            'programs' => $program,
            'name' => $name
        ]);
    }

}

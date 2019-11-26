<?php

//src/Controller/WildController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() : Response
    {
        return $this ->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }


    /**
     * @Route("/wild/show/{slug}", name="wild_show", defaults={"slug" = ""},
     * requirements={"slug" = "[a-z0-9\-]+"})
     */
    public function show(string $slug) : Response
    {
        $noSlug = "Aucune série séléctionnée, veuillez choisir une serie";
        if($slug=== '') {
             $slug=$noSlug;
        } else {
            $slug=ucwords(str_replace('-', ' ',$slug));
        }
        return $this->render('wild/show.html.twig', [
            'slug' => $slug,
        ]);
    }
}

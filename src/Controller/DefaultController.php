<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="liste_articles", methods={"GET"})
     */
    public function listeArticles(): Response
    {
        $url1 = $this->generateUrl('vue_article', ['id' => 1]);
        $url2 = $this->generateUrl('vue_article', ['id' => 2]);
        $url3 = $this->generateUrl('vue_article', ['id' => 3]);

        return $this->render('default/index.html.twig', [
            'url1' => $url1,
            'url2' => $url2,
            'url3' => $url3,
        ]);
    }

    /**
     * @Route("/{id}", name="vue_article", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function vueArticle($id)
    {
        return $this->render('default/vue.html.twig',[
            'id' => $id,
        ]);
        return new Response("<h1>Article ". $id . "</h1> <p>Ceci est l'article</p>");
    }

    /**
     * @Route("/article/ajouter", name="ajout_article")
     */
    public function ajouter(EntityManagerInterface $em)
    {
        $article = new Article();
        $article->setTitre("Titre de l'article");
        $article->setContenu("Ceci est le contenu de l'article");
        $article->setDateCreation(new \DateTime());

        $em->persist($article);
        $em->flush();
    }
}

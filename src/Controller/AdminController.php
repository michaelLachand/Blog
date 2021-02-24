<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class AdminController
 * @Route("/admin")
 */
class AdminController extends AbstractController

{

    /**
     * @Route("/article/ajouter", name="ajout_article")
     * @Route("/article/{id}/edition", name="edition_article", requirements={"id"="\d+"}, methods={"GET","POST"})
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function ajouter(Article $article = null,EntityManagerInterface $em, Request $request)
    {
        if($article === null){
            $article = new Article();
        }
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if($form->get('brouillon')->isClicked()){
                $article->setState('brouillon');
            } else {
                $article->setState('a publier');
            }

            if($article->getId() === null){
                $em->persist($article);
            }

            $em->flush();

            return $this->redirectToRoute('liste_articles');
        }

        return $this->render('default/ajout.html.twig',[
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/article/brouillon", name="brouillon_article")
     */
    public function brouillon(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy([
            'state' => 'brouillon'
        ]);

        return $this->render('default/index.html.twig', [
            'articles' => $articles,
            'brouillon'=> true,
        ]);

    }
}

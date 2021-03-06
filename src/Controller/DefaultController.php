<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\VerificationComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="liste_articles", methods={"GET"})
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function listeArticles(ArticleRepository $articleRepository): Response
    {

        $articles = $articleRepository->findBy([
            'state' => 'publie'
        ]);

        return $this->render('default/index.html.twig', [
            'articles' => $articles,
            'brouillon'=> false,
        ]);
    }

    /**
     * @Route("/{id}", name="vue_article", requirements={"id"="\d+"}, methods={"GET","POST"})
     * @param ArticleRepository $articleRepository
     * @param $id
     * @return Response
     */
    public function vueArticle(Article $article,Request $request,EntityManagerInterface $em,
                                VerificationComment $verificationComment)
    {

        $comment = new Comment();
        $comment->setArticle($article);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            if($verificationComment->commentaireNonAutorise($comment) === false){
                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('vue_article',['id'=> $article->getId()]);
            } else {
                $this->addFlash('danger', "Le commentaire contient un mot interdit");
            }
        }

        return $this->render('default/vue.html.twig',[
            'article' => $article,
            'form'=> $form->createView(),
        ]);
    }


}

<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++){
            $article = new Article();
            $article->setTitre("Article n°".$i);
            $article->setContenu("Ceci est le contenu de l'article".$i);

            $date = new \DateTime();
            $date->modify('-'.$i.'days');

            $article->setDateCreation($date);

            $manager->persist($article);
        }

        $manager->flush();
    }
}

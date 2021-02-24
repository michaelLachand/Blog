<?php

namespace App\Command;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PublishArticleCommand extends Command
{
    protected static $defaultName = 'app:publish-article';
    protected static $defaultDescription = 'Publi les articles a publier';

    private $articleRepository;
    private $em;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em,string $name = null)
    {
        $this->articleRepository = $articleRepository;
        $this->em = $em;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $articles = $this->articleRepository->findBy([
            'state'=> 'a publier'
        ]);

        foreach ($articles as $sarticle){
            $sarticle->setState('publie');
        }

        $this->em->flush();

        $io->success(count($articles) . ' articles publiés');

        return Command::SUCCESS;
    }
}

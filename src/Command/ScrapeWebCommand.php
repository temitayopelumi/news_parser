<?php
namespace App\Command;

use App\Repository\WebScrapingRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScrapeWebCommand extends Command{
    private $webScrapingRepository;

    protected static $defaultName = 'app:scrapeweb';

    public function __construct(WebScrapingRepository $webScrapingRepository)
    {
        $this->webScrapingRepository = $webScrapingRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Get article from techcabal')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

       
        $count = $this->webScrapingRepository->ScrapeWeb();
        

        $io->success(sprintf("Fetched latest article from techcabal"));

        return 0;
    }
}


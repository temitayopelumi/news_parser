<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Article;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Goutte\Client;
use Psr\Log\LoggerInterface;

class LuckyController extends AbstractController
{
/**
    * @Route("/lucky/number")
*/
    public function number(LoggerInterface $logger, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $client = new Client();
        $crawler = $client->request('GET', 'https://techcabal.com/');
        
        $result = $crawler->filter('article')->each(function($node){
            return $node;
        });

        foreach ($result as $e) {
            try {
                $title = $e->filter('.article-list-title')->text();
                $url = $e->filter('.article-list-title')->attr('href');
                $date = substr($url,22,10);
                $img = $e->filter('div > a > img')->attr('src');
                //convert date string to a date interface
                $date = \DateTime::createFromFormat('Y/m/d', $date);
                

                //update the database
                //check if title exists
                $article = $entityManager->getRepository(Article::class)->findOneBy(['title' => $title]);
                // $logger->info($article->title);
                // if article exists check if date is not equal and update
                if (!$article) {
                    $newArticle = new Article();
                    $newArticle->setTitle($title);
                    $newArticle->setDate($date);
                    $newArticle->setImage($img);
                    $entityManager->persist($newArticle);
                }
                else {
                    $article->setDate($date);
                }
               
                $entityManager->flush();
            }
            catch(Exception $e){
               $logger->info($e->getMessage());
            }
        }
        $number = random_int(0, 100);
        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}
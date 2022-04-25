<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SummonerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummonerController extends AbstractController
{
    #[Route('/summoners', name: 'summoners')]
    public function summoners(SummonerRepository $summonerRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('summoner/summoners.html.twig', [
            'summoners' => $summonerRepository->findAll(),
        ]);
    }
}

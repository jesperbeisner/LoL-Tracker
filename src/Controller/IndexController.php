<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Summoner;
use App\Form\SummonerType;
use App\Repository\SummonerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Jesperbeisner\RiotApiBundle\Exception\LoLApiException;
use Jesperbeisner\RiotApiBundle\Manager\RiotApiManager;
use Jesperbeisner\RiotApiBundle\Service\LoLChampionApiService;
use Jesperbeisner\RiotApiBundle\Service\LoLSummonerApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager, RiotApiManager $riotApiManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $summoner = new Summoner();

        $summonerForm = $this->createForm(SummonerType::class, $summoner);
        $summonerForm->handleRequest($request);

        if ($summonerForm->isSubmitted() && $summonerForm->isValid()) {
            try {
                /** @var LoLSummonerApiService $summonerApiService */
                $summonerApiService = $riotApiManager->getApiService(LoLSummonerApiService::class);
                $summonerData = $summonerApiService->findSummonerByUsername($summoner->getUsername(), $summoner->getServer());

                /** @var SummonerRepository $summonerRepository */
                $summonerRepository = $entityManager->getRepository(Summoner::class);
                if (null !== $summonerRepository->findSummonerByPuuid($summonerData->puuid)) {
                    $this->addFlash('error', 'The player already exists in the database');
                    return $this->redirectToRoute('index');
                }

                $summoner->setSummonerData($summonerData);

                $entityManager->persist($summoner);
                $entityManager->flush();

                $this->addFlash('success', 'The new player was successfully added');
                return $this->redirectToRoute('index');
            } catch (LoLApiException $e) {
                $this->addFlash('error', $e->getCode() . ' - ' . $e->getMessage());
                return $this->redirectToRoute('index');
            }
        }

        return $this->renderForm('index/index.html.twig', [
            'summonerForm' => $summonerForm,
        ]);
    }

    #[Route('/champions', name: 'champions')]
    public function champions(RiotApiManager $riotApiManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /** @var LoLChampionApiService $championApiService */
        $championApiService = $riotApiManager->getApiService(LoLChampionApiService::class);

        return $this->render('index/champions.html.twig', [
            'champions' => $championApiService->getAllChampions(),
        ]);
    }
}

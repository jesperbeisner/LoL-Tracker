<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\ActiveMatch;
use App\Entity\Summoner;
use App\Repository\ActiveMatchRepository;
use App\Repository\SummonerRepository;
use App\Service\TelegramApiService;
use Doctrine\ORM\EntityManagerInterface;
use Jesperbeisner\RiotApiBundle\Manager\RiotApiManager;
use Jesperbeisner\RiotApiBundle\Service\LoLChampionApiService;
use Jesperbeisner\RiotApiBundle\Service\LoLSpectatorApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckSummonersCommand extends Command
{
    protected static $defaultName = 'app:check-summoners';

    private EntityManagerInterface $entityManager;
    private RiotApiManager $riotApiManager;
    private TelegramApiService $telegramApiService;

    public function __construct(
        EntityManagerInterface $entityManager,
        RiotApiManager $riotApiManager,
        TelegramApiService $telegramApiService,
    ) {
        $this->entityManager = $entityManager;
        $this->riotApiManager = $riotApiManager;
        $this->telegramApiService = $telegramApiService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var SummonerRepository $summonerRepository */
        $summonerRepository = $this->entityManager->getRepository(Summoner::class);
        $summoners = $summonerRepository->findAllActiveSummoners();

        /** @var LoLChampionApiService $championApiService */
        $championApiService = $this->riotApiManager->getApiService(LoLChampionApiService::class);
        $champions = $championApiService->getAllChampions();

        foreach ($summoners as $summoner) {
            /** @var LoLSpectatorApiService $spectatorApiService */
            $spectatorApiService = $this->riotApiManager->getApiService(LoLSpectatorApiService::class);
            $activeMatchData = $spectatorApiService->findActiveMatch($summoner->getLolId(), $summoner->getServer());

            /** @var ActiveMatchRepository $activeMatchRepository */
            $activeMatchRepository = $this->entityManager->getRepository(ActiveMatch::class);
            $activeMatch = $activeMatchRepository->findActiveMatchForSummoner($summoner);

            // No match found: Check if an ActiveMatch is still open for this summoner and close it. Otherwise, do nothing
            // Match found: Check if completely new ActiveMatch and send Telegram. Otherwise, do nothing
            if (null === $activeMatchData) {
                if (null === $activeMatch) {
                    $output->writeln(sprintf("Nothing to do for summoner: %s", $summoner->getUsername()));
                } else {
                    $activeMatch->close();
                    $activeMatch->update();

                    $this->entityManager->flush();

                    $output->writeln(sprintf("Game closed for summoner: %s", $summoner->getUsername()));
                }
            } else {
                if (null === $activeMatch) {
                    $activeMatch = new ActiveMatch();
                    $activeMatch->setGameId($activeMatchData->gameId);
                    $activeMatch->setSummoner($summoner);

                    $this->entityManager->persist($activeMatch);
                    $this->entityManager->flush();

                    $this->telegramApiService->sendSummonerStartedGameMessage($summoner, $activeMatchData);

                    $output->writeln(sprintf("Game started for summoner: %s", $summoner->getUsername()));
                } else {
                    $output->writeln(sprintf("Game ongoing for summoner: %s", $summoner->getUsername()));
                }
            }
        }

        return Command::SUCCESS;
    }
}

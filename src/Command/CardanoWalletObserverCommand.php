<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\CardanoTxInit;
use App\Message\Cardano\SubjectTransactionsRefreshMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'kbin:cardano:refresh',
    description: 'This command allows refresh users transactions.'
)]
class CardanoWalletObserverCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $requests = $this->entityManager->getRepository(CardanoTxInit::class)->findForRefresh();

        foreach ($requests as $request) {
            /*
             * @var $request CardanoTxInit
             */
            $this->bus->dispatch(
                new SubjectTransactionsRefreshMessage(
                    $request->getId()
                )
            );
        }

        return Command::SUCCESS;
    }
}

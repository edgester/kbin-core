<?php

declare(strict_types=1);

namespace App\MessageHandler\ActivityPub\Outbox;

use App\Message\ActivityPub\Outbox\DeleteMessage;
use App\Message\ActivityPub\Outbox\DeliverMessage;
use App\Repository\MagazineRepository;
use App\Repository\UserRepository;
use App\Service\ActivityPub\Wrapper\DeleteWrapper;
use App\Service\ActivityPubManager;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
class DeleteHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly MagazineRepository $magazineRepository,
        private readonly DeleteWrapper $deleteWrapper,
        private readonly MessageBusInterface $bus,
        private readonly ActivityPubManager $activityPubManager,
        private readonly SettingsManager $settingsManager
    ) {
    }

    public function __invoke(DeleteMessage $message): void
    {
        if (!$this->settingsManager->get('KBIN_FEDERATION_ENABLED')) {
            return;
        }

        $entity = $this->entityManager->getRepository($message->type)->find($message->id);

        $activity = $this->deleteWrapper->build($entity, Uuid::v4()->toRfc4122());

        $this->deliver($this->userRepository->findAudience($entity->user), $activity);
        $this->deliver($this->activityPubManager->createInboxesFromCC($activity, $entity->user), $activity);
        $this->deliver($this->magazineRepository->findAudience($entity->magazine), $activity);
    }

    private function deliver(array $followers, array $activity)
    {
        foreach ($followers as $follower) {
            if (!$follower) {
                continue;
            }
            if (is_string($follower)) {
                $this->bus->dispatch(new DeliverMessage($follower, $activity));
                continue;
            }
            $this->bus->dispatch(new DeliverMessage($follower->apProfileId, $activity));
        }
    }
}

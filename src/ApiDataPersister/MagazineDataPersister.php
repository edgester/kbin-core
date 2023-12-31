<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\DTO\MagazineDto;
use App\Factory\MagazineFactory;
use App\Service\MagazineManager;
use Symfony\Bundle\SecurityBundle\Security;

final class MagazineDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private readonly MagazineManager $manager,
        private readonly MagazineFactory $factory,
        private readonly Security $security,
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof MagazineDto;
    }

    public function persist($data, array $context = []): MagazineDto
    {
        return $this->factory->createDto($this->manager->create($data, $this->security->getToken()->getUser()));
    }

    public function remove($data, array $context = [])
    {
    }
}

<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\DTO\PostCommentDto;
use App\Factory\PostCommentFactory;
use App\Service\PostCommentManager;
use Symfony\Bundle\SecurityBundle\Security;

final class PostCommentDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private readonly PostCommentManager $manager,
        private readonly PostCommentFactory $factory,
        private readonly Security $security,
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof PostCommentDto;
    }

    public function persist($data, array $context = []): PostCommentDto
    {
        return $this->factory->createDto($this->manager->create($data, $this->security->getToken()->getUser()));
    }

    public function remove($data, array $context = [])
    {
    }
}

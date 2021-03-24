<?php declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use App\DTO\MagazineDto;
use App\Entity\Magazine;
use App\Factory\MagazineFactory;
use App\Repository\MagazineRepository;

final class MagazineItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private MagazineRepository $magazineRepository;
    private MagazineFactory $magazineFactory;

    public function __construct(MagazineRepository $magazineRepository, MagazineFactory $magazineFactory)
    {
        $this->magazineRepository = $magazineRepository;
        $this->magazineFactory    = $magazineFactory;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return MagazineDto::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?MagazineDto
    {
        $magazine = $this->magazineRepository->findOneByName(['name' => $id]);

        if (!$magazine) {
            return null;
        }

        return $this->magazineFactory->createDto($magazine);
    }
}

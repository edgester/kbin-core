<?php

declare(strict_types=1);

namespace App\ArgumentValueResolver;

use App\Entity\Contracts\FavouriteInterface;
use App\Entity\Entry;
use App\Entity\EntryComment;
use App\Entity\Post;
use App\Entity\PostComment;
use App\Repository\EntryCommentRepository;
use App\Repository\EntryRepository;
use App\Repository\PostCommentRepository;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FavouriteResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly EntryRepository $entryRepository,
        private readonly EntryCommentRepository $entryCommentRepository,
        private readonly PostRepository $postRepository,
        private readonly PostCommentRepository $postCommentRepository
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return FavouriteInterface::class === $argument->getType()
            && !$argument->isVariadic()
            && $request->attributes->has('entityClass')
            && \in_array(
                $request->attributes->get('entityClass'),
                [
                    Entry::class,
                    EntryComment::class,
                    Post::class,
                    PostComment::class,
                ],
                true
            )
            && $request->attributes->has('id');
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        ['id' => $id, 'entityClass' => $entityClass] = $request->attributes->all();

        return match ($entityClass) {
            Entry::class => yield $this->entryRepository->find($id),
            EntryComment::class => yield $this->entryCommentRepository->find($id),
            Post::class => yield $this->postRepository->find($id),
            PostComment::class => yield $this->postCommentRepository->find($id),
            default => throw new \LogicException(),
        };
    }
}

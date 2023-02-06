<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Entity\Entry;
use App\Entity\EntryComment;
use App\Entity\Post;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class UrlExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function entryUrl(Entry $entry): string
    {
        return $this->urlGenerator->generate('entry_single', [
            'magazine_name' => $entry->magazine->name,
            'entry_id' => $entry->getId(),
            'slug' => $entry->slug === '' ? 'icon' : $entry->slug,
        ]);
    }

    public function entryFavouritesUrl(Entry $entry): string
    {
        return $this->urlGenerator->generate('entry_favourites', [
            'magazine_name' => $entry->magazine->name,
            'entry_id' => $entry->getId(),
            'slug' => $entry->slug === '' ? 'icon' : $entry->slug,
        ]);
    }

    public function entryVotersUrl(Entry $entry, string $type): string
    {
        return $this->urlGenerator->generate('entry_voters', [
            'magazine_name' => $entry->magazine->name,
            'entry_id' => $entry->getId(),
            'slug' => $entry->slug === '' ? 'icon' : $entry->slug,
            'type' => $type,
        ]);
    }

    public function entryCommentReplyUrl(EntryComment $comment): string
    {
        return $this->urlGenerator->generate('entry_comment_reply', [
            'magazine_name' => $comment->magazine->name,
            'entry_id' => $comment->entry->getId(),
            'slug' => empty($comment->entry->slug) ? 'icon' : $comment->entry->slug,
            'parent_comment_id' => $comment->getId(),
        ]);
    }


    public function postUrl(Post $post): string
    {
        return $this->urlGenerator->generate('post_single', [
            'magazine_name' => $post->magazine->name,
            'post_id' => $post->getId(),
            'slug' => $post->slug === '' ? 'icon' : $post->slug,
        ]);
    }
}

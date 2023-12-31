<?php

declare(strict_types=1);

namespace App\Event\Post;

use App\Entity\Post;

class PostBeforePurgeEvent
{
    public function __construct(public Post $post)
    {
    }
}

<?php declare(strict_types=1);

namespace App\DTO;

class UserSettingsDto
{
    public function __construct(
        public bool $notifyOnNewEntry = false,
        public bool $notifyOnNewEntryReply = false,
        public bool $notifyOnNewEntryCommentReply = true,
        public bool $notifyOnNewPost = false,
        public bool $notifyOnNewPostReply = false,
        public bool $notifyOnNewPostCommentReply = true,
        public bool $darkTheme = true,
        public bool $turboMode = false,
        public bool $hideImages = false,
        public bool $hideAdult = true,
        public bool $hideUserAvatars = true,
        public bool $hideMagazineAvatars = true,
        public bool $rightPosImages = false,
        public bool $entryPopup = false,
        public bool $postPopup = false,
        public bool $showProfileSubscriptions = true,
        public bool $showProfileFollowings = true,
        public string $homepage = 'front_subscribed',
        public ?array $featuredMagazines = null,
    ) {
    }
}

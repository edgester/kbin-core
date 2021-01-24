<?php declare(strict_types = 1);

namespace App\DTO;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Magazine;
use App\Entity\User;

class EntryDto
{
    const ENTRY_TYPE_ARTICLE = 'artykul';
    const ENTRY_TYPE_LINK = 'link';

    /**
     * @var int|null
     */
    private $id;

    /**
     * @Assert\NotBlank()
     *
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $body = null;

    /**
     * @var string|null
     */
    private $url = null;

    /**
     * @Assert\NotBlank()
     *
     * @var Magazine|null
     */
    private $magazine;

    /**
     * @var User
     */
    private $user;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (null === $this->getBody() && null === $this->getUrl()) {
            $this->buildViolation($context, 'url');
            $this->buildViolation($context, 'body');
        }
    }

    private function buildViolation(ExecutionContextInterface $context, $path)
    {
        $context->buildViolation('This value should not be blank.')
            ->atPath($path)
            ->addViolation();
    }

    public function getType(): string
    {
        if ($this->getBody()) {
            return self::ENTRY_TYPE_ARTICLE;
        }

        return self::ENTRY_TYPE_LINK;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string|null $body
     */
    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return Magazine|null
     */
    public function getMagazine(): ?Magazine
    {
        return $this->magazine;
    }

    /**
     * @param Magazine $magazine
     */
    public function setMagazine(Magazine $magazine): void
    {
        $this->magazine = $magazine;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
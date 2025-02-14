<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: 'App\Entity\Discussion')]
    private $discussions;

    public function __construct()
    {
        $this->discussions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre): void
    {
        $this->titre = $titre;
    }

    public function getDiscussions(): Collection
    {
        return $this->discussions;
    }

    public function getLastDiscussionDate(): ?\DateTimeInterface
    {
        $discussions = $this->getDiscussions();
        if ($discussions->isEmpty()) {
            return null;
        }

        $lastDiscussion = $discussions->last();

        return $lastDiscussion->getCreatedAt();
    }


    public function getDiscussionCount(): int
    {
        return count($this->discussions);
    }


}

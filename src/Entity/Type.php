<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Bag>
     */
    #[ORM\OneToMany(targetEntity: Bag::class, mappedBy: 'type')]
    private Collection $bags;

    public function __construct()
    {
        $this->bags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Bag>
     */
    public function getBags(): Collection
    {
        return $this->bags;
    }

    public function addBag(Bag $bag): static
    {
        if (!$this->bags->contains($bag)) {
            $this->bags->add($bag);
            $bag->setType($this);
        }

        return $this;
    }

    public function removeBag(Bag $bag): static
    {
        if ($this->bags->removeElement($bag)) {
            // set the owning side to null (unless already changed)
            if ($bag->getType() === $this) {
                $bag->setType(null);
            }
        }

        return $this;
    }
}

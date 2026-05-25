<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(nullable: false)]
    private int $id;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(length: 255, nullable: false)]
    private string $reference;

    #[ORM\Column(length: 255, nullable: false)]
    private string $image;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Category $category;

    /**
     * @var Collection<int, ProductInfo>
     */
    #[ORM\OneToMany(targetEntity: ProductInfo::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $info;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $offers;

    public function __construct()
    {
        $this->info = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, ProductInfo>
     */
    public function getInfo(): Collection
    {
        return $this->info;
    }

    public function addInfo(ProductInfo $info): static
    {
        if (!$this->info->contains($info)) {
            $this->info->add($info);
            $info->setProduct($this);
        }

        return $this;
    }

    public function removeInfo(ProductInfo $info): static
    {
        if ($this->info->removeElement($info)) {
            // set the owning side to null (unless already changed)
            if ($info->getProduct() === $this) {
                $info->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setProduct($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getProduct() === $this) {
                $offer->setProduct(null);
            }
        }

        return $this;
    }
}

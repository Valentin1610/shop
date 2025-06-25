<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_products = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $Image = null;

    #[ORM\Column(length: 50)]
    private ?string $emplacement_rayonnage = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(targetEntity: CategoriesProducts::class)]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "id", nullable: true)]
    private ?CategoriesProducts $category = null;

    #[ORM\ManyToOne(targetEntity: Suppliers::class)]
    #[ORM\JoinColumn(name: "id_suppliers", referencedColumnName: "id_suppliers")] // Ajouter cette ligne pour spécifier le nom correct de la colonne
    private ?Suppliers $supplier = null;

    public function getid_products(): ?int
    {
        return $this->id_products;
    }
    public function setid_products(int $id_products): static
    {
        $this->id_products = $id_products;

        return $this;
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }

    public function getEmplacementRayonnage(): ?string
    {
        return $this->emplacement_rayonnage;
    }

    public function setEmplacementRayonnage(string $emplacement_rayonnage): static
    {
        $this->emplacement_rayonnage = $emplacement_rayonnage;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCategory(): ?CategoriesProducts
    {
        return $this->category;
    }

    public function setCategory(?CategoriesProducts $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSupplier(): ?Suppliers
    {
        return $this->supplier;
    }

    public function setSupplier(?Suppliers $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }
}
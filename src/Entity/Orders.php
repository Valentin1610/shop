<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_orders = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $preparation_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_departure_quay = null;

    #[ORM\Column]
    private ?int $order_number = null;

    #[ORM\Column(length: 50)]
    private ?string $total = null;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: "status_id", referencedColumnName: "id_status", nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "id_users", referencedColumnName: "id_users", nullable: false)]
    private ?Users $user = null;

    public function getId_orders(): ?int
    {
        return $this->id_orders;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getPreparationDate(): ?\DateTimeInterface
    {
        return $this->preparation_date;
    }

    public function setPreparationDate(\DateTimeInterface $preparation_date): static
    {
        $this->preparation_date = $preparation_date;

        return $this;
    }

    public function getDateDepartureQuay(): ?\DateTimeInterface
    {
        return $this->date_departure_quay;
    }

    public function setDateDepartureQuay(\DateTimeInterface $date_departure_quay): static
    {
        $this->date_departure_quay = $date_departure_quay;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOrderNumber(): ?int
    {
        return $this->order_number;
    }

    public function setOrderNumber(int $order_number): static
    {
        $this->order_number = $order_number;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getIdStatus(): ?int
    {
        return $this->status;
    }

    public function setIdStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(Users $user): static
    {
        $this->user = $user;

        return $this;
    }
}

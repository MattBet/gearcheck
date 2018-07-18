<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShippingRepository")
 */
class Shipping
{
    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="cartProducts")
     */
    private $product;
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Cart", inversedBy="cartProducts")
     */
    private $cart;

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

}

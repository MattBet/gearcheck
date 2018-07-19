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
     * @ORM\ManyToOne(targetEntity="App\Entity\Cart", inversedBy="products")
     */
    private $cart;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="shippings")
     */
    private $product;

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

<?php
class Product {
    private string $id;
    private string $name;
    private float $price;
    private int $qty;

    public function __construct($id, $name, $price, $qty) {
        $this->id    = trim($id);
        $this->name  = trim($name);
        $this->price = (float)$price;
        $this->qty   = (int)$qty;
    }

    public function getId()   { return $this->id; }
    public function getName() { return $this->name; }
    public function getPrice(){ return $this->price; }
    public function getQty()  { return $this->qty; }

    public function amount(): float {
        if ($this->qty <= 0) return 0;
        return $this->price * $this->qty;
    }

    public function isValidQty(): bool {
        return $this->qty > 0;
    }
}

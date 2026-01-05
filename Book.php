<?php
class Book
{
    private string $id;
    private string $title;
    private int $qty;

    public function __construct(string $id, string $title, int $qty)
    {
        $this->id = $id;
        $this->title = $title;
        $this->qty = $qty;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function getStatus(): string
    {
        return $this->qty > 0 ? "Available" : "Out of stock";
    }
}

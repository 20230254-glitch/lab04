<?php
class Student
{
    private string $id;
    private string $name;
    private float $gpa;

    // Constructor
    public function __construct(string $id, string $name, float $gpa)
    {
        $this->id = $id;
        $this->name = $name;
        $this->gpa = $gpa;
    }

    // Getter
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGpa(): float
    {
        return $this->gpa;
    }

    // Xếp loại
    public function rank(): string
    {
        if ($this->gpa >= 3.2) {
            return "Giỏi";
        } elseif ($this->gpa >= 2.5) {
            return "Khá";
        }
        return "Trung bình";
    }
}

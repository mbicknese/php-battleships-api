<?php

namespace App\Entity;

class Item
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * Item constructor.
     * @param string $name
     */
    private function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     * @return Item
     */
    public static function fromName(string $name): self
    {
        return new self($name);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 */
class Test
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uid;

    /**
     * @ORM\Column(type="json")
     */
    private $questions = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginTimestamp;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishTimestamp;

    /**
     * @ORM\Column(type="boolean")
     */
    private $finished = false;

    /**
     * @ORM\Column(type="smallint")
     */
    private $correct = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getQuestions(): ?array
    {
        return $this->questions;
    }

    public function setQuestions(array $questions): self
    {
        $this->questions = $questions;

        return $this;
    }

    public function getBeginTimestamp(): ?\DateTimeInterface
    {
        return $this->beginTimestamp;
    }

    public function setBeginTimestamp(\DateTimeInterface $beginTimestamp): self
    {
        $this->beginTimestamp = $beginTimestamp;

        return $this;
    }

    public function getFinishTimestamp(): ?\DateTimeInterface
    {
        return $this->finishTimestamp;
    }

    public function setFinishTimestamp(?\DateTimeInterface $finishTimestamp): self
    {
        $this->finishTimestamp = $finishTimestamp;

        return $this;
    }

    public function isFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    public function getCorrect(): ?int
    {
        return $this->correct;
    }

    public function setCorrect(int $correct): self
    {
        $this->correct = $correct;

        return $this;
    }
}

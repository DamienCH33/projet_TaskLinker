<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name:'title',length: 255, type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(name:'description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name:'deadline',type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deadline = null;

    #[ORM\Column(name:'status', length: 20, type: Types::TEXT)]
    private ?string $status = null;

    // ✅ ManyToMany vers Employee
    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'tasks')]
    #[ORM\JoinTable(name: 'task_employee')]
    private Collection $employees;

    // ✅ ManyToOne vers Project
    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Project $project = null;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = ucfirst($title);
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = ucfirst($description);
        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): static
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            $employee->addTask($this);
        }
        return $this;
    }

    public function removeEmployee(Employee $employee): static
    {
        if ($this->employees->removeElement($employee)) {
            $employee->removeTask($this);
        }
        return $this;
    }
}

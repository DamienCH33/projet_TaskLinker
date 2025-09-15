<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTime $startDate = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(mappedBy: 'employees', targetEntity: Project::class)]
    private Collection $projects;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Task::class)]
    private Collection $tasks;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    // ID
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    // Firstname 
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    // Lastname
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    // Email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    // Password
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    // Status
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    // Start Date
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    // Projects
    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addEmployee($this);
        }

        return $this;
    }
    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeEmployee($this);
        }

        return $this;
    }
    // Tasks
    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setEmployee($this);
        }
        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            if ($task->getEmployee() === $this) {
                $task->setEmployee(null);
            }
        }
        return $this;
    }
}

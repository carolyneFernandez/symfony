<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
//not blannk para queel valor no sea vacio
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[A-Za-z\s]+$/")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[A-Za-z\s]+$/")
     */
    private $firstname;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = "00000",
     *      max = "99999",
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $roadNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roadName;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sex;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account", mappedBy="owner", orphanRemoval=true)
     */
    private $accounts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Account", inversedBy="people")
     */
    private $beneficiaire;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->beneficiaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getRoadNumber(): ?int
    {
        return $this->roadNumber;
    }

    public function setRoadNumber(?int $roadNumber): self
    {
        $this->roadNumber = $roadNumber;

        return $this;
    }

    public function getRoadName(): ?string
    {
        return $this->roadName;
    }

    public function setRoadName(string $roadName): self
    {
        $this->roadName = $roadName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getSex(): ?bool
    {
        return $this->sex;
    }

    public function setSex(bool $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setOwner($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->contains($account)) {
            $this->accounts->removeElement($account);
            // set the owning side to null (unless already changed)
            if ($account->getOwner() === $this) {
                $account->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getBeneficiaire(): Collection
    {
        return $this->beneficiaire;
    }

    public function addBeneficiaire(Account $beneficiaire): self
    {
        if (!$this->beneficiaire->contains($beneficiaire)) {
            $this->beneficiaire[] = $beneficiaire;
        }

        return $this;
    }

    public function removeBeneficiaire(Account $beneficiaire): self
    {
        if ($this->beneficiaire->contains($beneficiaire)) {
            $this->beneficiaire->removeElement($beneficiaire);
        }

        return $this;
    }
}

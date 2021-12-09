<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Email déjà utilisé !"
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire au moin 8 caractères !")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vos mot de passe ne correspondent pas !")
     */
    private $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sender;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="recipient", orphanRemoval=true)
     */
    private $recipient;

    public function __construct()
    {
        $this->sender = new ArrayCollection();
        $this->recipient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirm_password;
    }

    public function setConfirmPassword(string $confirm_password): self
    {
        $this->confirm_password = $confirm_password;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSender(): Collection
    {
        return $this->sender;
    }

    public function addSender(Message $sender): self
    {
        if (!$this->sender->contains($sender)) {
            $this->sender[] = $sender;
            $sender->setSender($this);
        }

        return $this;
    }

    public function removeSender(Message $sender): self
    {
        if ($this->sender->removeElement($sender)) {
            // set the owning side to null (unless already changed)
            if ($sender->getSender() === $this) {
                $sender->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getRecipient(): Collection
    {
        return $this->recipient;
    }

    public function addRecipient(Message $recipient): self
    {
        if (!$this->recipient->contains($recipient)) {
            $this->recipient[] = $recipient;
            $recipient->setRecipient($this);
        }

        return $this;
    }

    public function removeRecipient(Message $recipient): self
    {
        if ($this->recipient->removeElement($recipient)) {
            // set the owning side to null (unless already changed)
            if ($recipient->getRecipient() === $this) {
                $recipient->setRecipient(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}

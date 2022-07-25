<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string", length=255)
     */
    private string $username;

    /**
     * @Column(type="string", length=255)
     */
    private string $firstname;

    /**
     * @Column(type="string", length=255)
     */
    private string $lastname;

    /**
     * @Column(type="string", length=255)
     */
    private string $password;

    /**
     * @Column(type="string", length=255)
     */
    private string $email;

    /**
     * @OneToMany(targetEntity="\App\Entity\Forum\Post", mappedBy="author")
     */
    private mixed $forumPosts;

    /**
     * @OneToMany(targetEntity="\App\Entity\Blog\Post", mappedBy="author")
     */
    private mixed $blogPosts;

    /**
     * @Column(type="boolean", name="logged_in", options={"default": false})
     */
    private bool $loggedIn;

    /**
     * @Column(type="boolean", name="is_admin", options={"default": false})
     */
    private bool $isAdmin;

    /**
     * @Column(type="string", length=255, name="last_activity", nullable=true)
     */
    private ?string $lastActivity;

    /**
     * @Column(type="json")
     */
    private array $roles = [];

    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname(string $firstname): User {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname(string $lastname): User {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForumPosts() {
        return $this->forumPosts;
    }

    /**
     * @param mixed $forumPosts
     * @return User
     */
    public function setForumPosts($forumPosts) {
        $this->forumPosts = $forumPosts;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlogPosts(): mixed {
        return $this->blogPosts;
    }

    /**
     * @param mixed $blogPosts
     * @return User
     */
    public function setBlogPosts(mixed $blogPosts): User {
        $this->blogPosts = $blogPosts;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool {
        return $this->loggedIn;
    }

    /**
     * @param bool $loggedIn
     * @return User
     */
    public function setLoggedIn(bool $loggedIn): User {
        $this->loggedIn = $loggedIn;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     * @return User
     */
    public function setIsAdmin(bool $isAdmin): User {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastActivity(): ?string {
        return $this->lastActivity;
    }

    /**
     * @param string $lastActivity
     * @return User
     */
    public function setLastActivity(string $lastActivity): User {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    public function getRoles(): array {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials() {
        //
    }

    public function getUserIdentifier(): string {
        return (string) $this->username;
    }

    public function getSalt() {
        return null;
    }
}

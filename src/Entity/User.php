<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="This email is taken.", groups="registration")
 * @UniqueEntity(fields={"username"}, message="This username is taken.", groups="registration")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface {
    /**
     * Id.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Username
     *
     * @Column(type="string", length=255, unique=true)
     */
    private string $username;

    /**
     * Firstname.
     *
     * @Column(type="string", length=255)
     */
    private string $firstname;

    /**
     * Last name.
     *
     * @Column(type="string", length=255)
     */
    private string $lastname;

    /**
     * Password.
     *
     * @Column(type="string", length=255)
     */
    private string $password;

    /**
     * Email.
     *
     * @Column(type="string", length=255, unique=true)
     * @Assert\Email(groups="registration")
     */
    private string $email;

    /**
     * Blog posts.
     *
     * @OneToMany(targetEntity="\App\Entity\Blog\Post", mappedBy="author")
     */
    private mixed $blogPosts;

    /**
     * Is the user logged in?
     *
     * @Column(type="boolean", name="logged_in", options={"default": false})
     */
    private bool $loggedIn;

    /**
     * Is the user an administrator?
     *
     * @Column(type="boolean", name="is_admin", options={"default": false})
     */
    private bool $isAdmin;

    /**
     * Last activity.
     *
     * @Column(type="string", length=255, name="last_activity", nullable=true)
     */
    private ?string $lastActivity;

    /**
     * Profile image.
     *
     * @Column(type="string", nullable=true)
     */
    private ?string $profileImage;

    /**
     * User roles.
     *
     * @Column(type="json")
     */
    private array $roles = [];

    /**
     * Get ID.
     *
     * @return integer|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username Username.
     * @return User
     */
    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname(): string {
        return $this->firstname;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname Firstname.
     * @return User
     */
    public function setFirstname(string $firstname): User {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname(): string {
        return $this->lastname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname Lastname.
     * @return User
     */
    public function setLastname(string $lastname): User {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password Password.
     * @return User
     */
    public function setPassword(string $password): User {
        $this->password = $password;
        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email Email.
     * @return User
     */
    public function setEmail(string $email): User {
        $this->email = $email;
        return $this;
    }

    /**
     * Get forum posts.
     *
     * @return mixed
     */
    public function getForumPosts() {
        return $this->forumPosts;
    }

    /**
     * Set forum posts.
     *
     * @param mixed $forumPosts Forum posts.
     * @return User
     */
    public function setForumPosts($forumPosts) {
        $this->forumPosts = $forumPosts;
        return $this;
    }

    /**
     * Get blog posts.
     *
     * @return mixed
     */
    public function getBlogPosts(): mixed {
        return $this->blogPosts;
    }

    /**
     * Set blog posts.
     *
     * @param mixed $blogPosts Blog posts.
     * @return User
     */
    public function setBlogPosts(mixed $blogPosts): User {
        $this->blogPosts = $blogPosts;
        return $this;
    }

    /**
     * Is user logged in?
     *
     * @return boolean
     */
    public function isLoggedIn(): bool {
        return $this->loggedIn;
    }

    /**
     * Set logged in status.
     *
     * @param boolean $loggedIn Logged in.
     * @return User
     */
    public function setLoggedIn(bool $loggedIn): User {
        $this->loggedIn = $loggedIn;
        return $this;
    }

    /**
     * Is user admin?
     *
     * @return boolean
     */
    public function isAdmin(): bool {
        return $this->isAdmin;
    }

    /**
     * Set user admin.
     *
     * @param boolean $isAdmin Admin.
     * @return User
     */
    public function setIsAdmin(bool $isAdmin): User {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * Get last activity.
     *
     * @return string|null
     */
    public function getLastActivity(): ?string {
        return $this->lastActivity;
    }

    /**
     * Set last activity.
     *
     * @param string $lastActivity Last activity.
     * @return User
     */
    public function setLastActivity(string $lastActivity): User {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    /**
     * Get roles.
     *
     * @return array|string[] Array of roles.
     */
    public function getRoles(): array {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Set roles.
     *
     * @param array $roles Array of roles.
     * @return $this
     */
    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get profile image.
     *
     * @return string|null
     */
    public function getProfileImage(): ?string {
        return $this->profileImage;
    }

    /**
     * Set profile image.
     *
     * @param string|null $profileImage Profile image name.
     */
    public function setProfileImage(?string $profileImage): void {
        $this->profileImage = $profileImage;
    }

    /**
     * Erase credentials.
     *
     * @return void
     */
    public function eraseCredentials() {
        //
    }

    /**
     * Get user identifier.
     *
     * @return string
     */
    public function getUserIdentifier(): string {
        return (string) $this->username;
    }

    /**
     * Get salt.
     *
     * @return null
     */
    public function getSalt() {
        return null;
    }
}

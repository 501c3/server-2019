<?php

namespace App\Entity\Access;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="user",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"}),
 *     @ORM\UniqueConstraint(name="username_UNIQUE", columns={"username"})},
 *     indexes={@ORM\Index(name="authenticator_UNIQUE", columns={"authenticator", "authenticator_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Access\UserRepository")
 *
 * @UniqueEntity(
 *     fields = {"email","username"},
 *     message = "Previously registered."
 * )
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=180, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=180, nullable=false)
     *
     * @Groups("main")
     *
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    private $passwordRequestedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="expire_at", type="datetime", nullable=true)
     */
    private $expireAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="authenticator", type="string", length=20, nullable=true)
     */
    private $authenticator;

    /**
     * @var string|null
     *
     * @ORM\Column(name="authenticator_id", type="string", length=60, nullable=true)
     */
    private $authenticatorId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
     */
    private $confirmationToken;

    /**
     * @var string|null
     *
     * @ORM\Column(name="access_token", type="string", length=255, nullable=true)
     */
    private $accessToken;

    /**
     * @var string|null
     *
     * @ORM\Column(name="refresh_token", type="string", length=255, nullable=true)
     */
    private $refreshToken;

    /**
     * @var array|null
     *
     * @ORM\Column(name="roles", type="json", nullable=true)
     */
    private $roles;

    /**
     * @var array|null
     *
     * @ORM\Column(name="info", type="json", nullable=true)
     */
    private $info;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Access\Channel", inversedBy="user")
     * @ORM\JoinTable(name="user_has_channel",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="channel_channel", referencedColumnName="channel")
     *   }
     * )
     */
    private $channel;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Access\Controller", inversedBy="user")
     * @ORM\JoinTable(name="user_has_controller",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="controller_id", referencedColumnName="id")
     *   }
     * )
     */
    private $controller;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Access\Workarea", inversedBy="user")
     * @ORM\JoinTable(name="user_has_workarea",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="workarea_id", referencedColumnName="id")
     *   }
     * )
     */
    private $workarea;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->channel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->controller = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workarea = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return User
     */
    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPasswordRequestedAt(): ?\DateTime
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param \DateTime|null $passwordRequestedAt
     * @return User
     */
    public function setPasswordRequestedAt(?\DateTime $passwordRequestedAt): User
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     * @return User
     */
    public function setLastLogin(?\DateTime $lastLogin): User
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpireAt(): ?\DateTime
    {
        return $this->expireAt;
    }

    /**
     * @param \DateTime|null $expireAt
     * @return User
     */
    public function setExpireAt(?\DateTime $expireAt): User
    {
        $this->expireAt = $expireAt;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
       return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getAvatarUrl(int $size=null):string
    {
        $url = 'https://robohash.org/'.$this->getEmail();

        if ($size) {
            $url .= sprintf('?size=%dx%d',$size,$size);
        }

        return $url;
    }

    /**
     * @return string|null
     */
    public function getAuthenticator(): ?string
    {
        return $this->authenticator;
    }

    /**
     * @param string|null $authenticator
     * @return User
     */
    public function setAuthenticator(?string $authenticator): User
    {
        $this->authenticator = $authenticator;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthenticatorId(): ?string
    {
        return $this->authenticatorId;
    }

    /**
     * @param string|null $authenticatorId
     * @return User
     */
    public function setAuthenticatorId(?string $authenticatorId): User
    {
        $this->authenticatorId = $authenticatorId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string|null $confirmationToken
     * @return User
     */
    public function setConfirmationToken(?string $confirmationToken): User
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string|null $accessToken
     * @return User
     */
    public function setAccessToken(?string $accessToken): User
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @param string|null $refreshToken
     * @return User
     */
    public function setRefreshToken(?string $refreshToken): User
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getInfo(): ?array
    {
        return $this->info;
    }

    /**
     * @param array|null $info
     * @return User
     */
    public function setInfo(?array $info): User
    {
        $this->info = $info;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     * @return User
     */
    public function setCreatedAt(?\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getChannel(): Collection
    {
        return $this->channel;
    }

    /**
     * @param Collection $channel
     * @return User
     */
    public function setChannel(Collection $channel): User
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getController(): Collection
    {
        return $this->controller;
    }

    /**
     * @param Collection $controller
     * @return User
     */
    public function setController(Collection $controller): User
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getWorkarea(): Collection
    {
        return $this->workarea;
    }

    /**
     * @param Collection $workarea
     * @return User
     */
    public function setWorkarea(Collection $workarea): User
    {
        $this->workarea = $workarea;
        return $this;
    }


    /** @return string */
    public function getName() : string
    {
        return "This name.";
    }
}

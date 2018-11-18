<?php

namespace App\Entity\Access;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="confirmation_token_UNIQUE", columns={"confirmation_token"}), @ORM\UniqueConstraint(name="email_canonical_UNIQUE", columns={"email_canonical"}), @ORM\UniqueConstraint(name="username_canonical_UNIQUE", columns={"username_canonical"})}, indexes={@ORM\Index(name="authenticator_UNIQUE", columns={"authenticator", "authenticator_id"})})
 * @ORM\Entity
 */
class User
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
     * @var string|null
     *
     * @ORM\Column(name="access_token", type="string", length=255, nullable=true)
     */
    private $accessToken;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=180, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_canonical", type="string", length=180, nullable=false)
     */
    private $emailCanonical;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="expire_at", type="datetime", nullable=true)
     */
    private $expireAt;

    /**
     * @var json|null
     *
     * @ORM\Column(name="info", type="json", nullable=true)
     */
    private $info;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mobile", type="string", length=15, nullable=true)
     */
    private $mobile;

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
     * @var string|null
     *
     * @ORM\Column(name="refresh_token", type="string", length=255, nullable=true)
     */
    private $refreshToken;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", length=0, nullable=false)
     */
    private $roles;

    /**
     * @var string|null
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=180, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="username_canonical", type="string", length=180, nullable=false)
     */
    private $usernameCanonical;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Access\Channel", mappedBy="user")
     */
    private $channelChannel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Access\Controller", mappedBy="user")
     */
    private $controller;

    /**
     * @var \Doctrine\Common\Collections\Collection
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
        $this->channelChannel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->controller = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workarea = new \Doctrine\Common\Collections\ArrayCollection();
    }

}

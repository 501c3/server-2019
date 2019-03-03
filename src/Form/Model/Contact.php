<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/16/19
 * Time: 9:29 AM
 */

namespace App\Form\Model;

use App\Validator\UniqueUser;

/**
 * Class Contact
 * @package App\Form\Model
 *
 * @UniqueUser()
 */

class Contact
{
    /** @var string|null */
    private $phone;

    /** @var string|null */
    private $mobile;

    /** @var string|null */
    private $username;

    /** @var string|null */
    private $email;

    /** @var string|null */
    private $password;


    public function __construct(?string $email=null, ?string $username=null, ?string $password=null,
                                ?string $mobile=null, ?string $phone=null)
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->mobile = $mobile;
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }


    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }



    public function toArray()
    {
        return ['phone'=>$this->phone,
                'mobile'=>$this->mobile,
                'email'=>$this->email,
                'username'=>$this->username,
                'password'=>$this->password];
    }

    public function isValid(): bool
    {
        return $this->email && $this->username && $this->password;
    }

    public function getErrors(): array
    {
        $errors = [];
        if(!$this->email) {
            $errors['email']='Email missing.';
        }
        if(!$this->username) {
            $errors['username']='Username missing.';
        }
        if(!$this->password) {
            $missing['password']='Password missing';
        }
    }

    /**
     * @param string|null $phone
     * @return Contact
     */
    public function setPhone(?string $phone): Contact
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string|null $mobile
     * @return Contact
     */
    public function setMobile(?string $mobile): Contact
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @param string|null $username
     * @return Contact
     */
    public function setUsername(?string $username): Contact
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string|null $email
     * @return Contact
     */
    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string|null $password
     * @return Contact
     */
    public function setPassword(?string $password): Contact
    {
        $this->password = $password;
        return $this;
    }


}
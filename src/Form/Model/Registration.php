<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/16/19
 * Time: 9:26 AM
 */

namespace App\Form\Model;




use App\Validator\UniqueUser;

/**
 * Class Registration
 * @package App\Form\Model
 *
 * @UniqueUser()
 */

class Registration
{
    /** @var Name */
    private $name;

    /** @var Address */
    private $address;

    /** @var Contact */
    private $contact;

    /** @var bool */
    private $agree = false;



    public function __construct(Name $name, Address $address, Contact $contact, bool $agree)
    {
        $this->name = $name;
        $this->address = $address;
        $this->contact = $contact;
        $this->agree = $agree;
    }

    /**
     * @return Name
     */

    public function getName() : ?Name
    {
        return $this->name;
    }

    /**
     * @return Address
     */
    public function getAddress() : Address
    {
        return $this->address;
    }


    /**
     * @return Contact
     */
    public function getContact() : Contact
    {
        return $this->contact;
    }


    public function getAgree() : bool
    {
        return $this->agree;
    }

    /**
     * @param Name $name
     * @return Registration
     */
    public function setName(Name $name): Registration
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Address $address
     * @return Registration
     */
    public function setAddress(Address $address): Registration
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param Contact $contact
     * @return Registration
     */
    public function setContact(Contact $contact): Registration
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @param bool $agree
     * @return Registration
     */
    public function setAgree(bool $agree): Registration
    {
        $this->agree = $agree;
        return $this;
    }




}
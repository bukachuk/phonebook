<?php

namespace Project\Entity;

use Symfony\Component\HttpFoundation\Request;

/**
 * Phonebook entity
 *
 * @Entity @Table(name="phonebook")
 * @Entity(repositoryClass="\Project\Repository\PhoneBookRepository")
 */
class PhoneBook
{
    /**
     * @var int
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * First name
     *
     * @Column(type="string")
     * @var string $firstName
     */
    private $firstName;

    /**
     * Last name
     *
     * @Column(type="string", nullable=true)
     * @var string $lastName
     */
    private $lastName;

    /**
     * Phone number
     *
     * @Column(type="string")
     * @var string $phone
     */
    private $phone;

    /**
     * Country Code
     *
     * @Column(type="string", length=2, nullable=true)
     * @var string $countryCode
     */
    private $countryCode;

    /**
     * Time ZONE
     *
     * @Column(type="string", nullable=true)
     * @var string $timeZone
     */
    private $timeZone;

    /**
     * Inserted DateTime
     *
     * @Column(type="datetime")
     * @var DateTime $insertedOn
     */
    private $insertedOn;

    /**
     * Last update DateTime
     *
     * @Column(type="datetime", nullable=true)
     * @var DateTime $updatedOn
     */
    private $updatedOn;

    public function __construct()
    {
        $this->insertedOn = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     */
    public function setTimeZone(string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

    /**
     * @return DateTime
     */
    public function getInsertedOn(): DateTime
    {
        return $this->insertedOn;
    }

    /**
     * @param DateTime $insertedOn
     */
    public function setInsertedOn(DateTime $insertedOn): void
    {
        $this->insertedOn = $insertedOn;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedOn(): DateTime
    {
        return $this->updatedOn;
    }

    /**
     * @param DateTime $updatedOn
     */
    public function setUpdatedOn(DateTime $updatedOn): void
    {
        $this->updatedOn = $updatedOn;
    }

    public function handleRequest(Request $request){
        $this->firstName = $request->get('firstName');
        $this->lastName = $request->get('lastName');
        $this->phone = $request->get('phone');
        $this->countryCode = $request->get('countryCode');
        $this->timeZone = $request->get('timeZone');
        $this->updatedOn = new \DateTime();
    }
}

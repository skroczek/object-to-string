<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\Tests\ObjectToString\Metadata\Driver\Fixtures;

use SK\ObjectToString\Annotation\ObjectToString;

/**
 * Class Email.
 *
 * @ObjectToString(name="name", format="name", params={"name": "name"})
 * @ObjectToString(name="email", format="email", params={"email": "email"})
 * @ObjectToString(name="email_alternative", format="email", params={"email": "emailAlternative"})
 * @ObjectToString(name="full_email", format="name ~ ' <' ~ email ~ '>'", params={"email": "email", "name": "name"})
 * @ObjectToString(name="full_email_alternative", format="name ~ ' <' ~ email ~ '>'", params={"email": "emailAlternative", "name": "name"})
 * @ObjectToString(name="full_email_validated", format="_this.getName() ~ (_this.isValidated() ? ' (validated)') ~' <' ~ _this.getEmail() ~ '>'")
 */
class Email
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $emailAlternative;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $validated = false;

    /**
     * Email constructor.
     *
     * @param string $email
     * @param string $emailAlternativ
     * @param string $name
     */
    public function __construct($name = null, $email = null, $emailAlternative = null, $validated = false)
    {
        $this->email = $email;
        $this->emailAlternative = $emailAlternative;
        $this->name = $name;
        $this->validated = $validated;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAlternative()
    {
        return $this->emailAlternative;
    }

    /**
     * @param string $emailAlternativ
     *
     * @return Email
     */
    public function setEmailAlternative($emailAlternative)
    {
        $this->emailAlternative = $emailAlternative;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Email
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValidated()
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     *
     * @return Email
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }
}

<?php

namespace Src\Service\Sender\impl\email\model;

class EmailAuthor
{
    private string $fromEmail;
    private string $fromName;

    /**
     * @param string $fromEmail
     * @param string $fromName
     */
    public function __construct(string $fromEmail, string $fromName)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }
}
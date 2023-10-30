<?php

namespace Src\Model\DTO\Read;

class UserReadDto
{
    public int $id;
    public string $name;
    public string $surname;
    public string $email;
    public ?string $filename;

    public function __construct($array) {
        $this->id = $array['id'];
        $this->name = $array['name'];
        $this->surname = $array['surname'];
        $this->email = $array['email'];
        $this->filename = $array['filename'];
    }
}
<?php

namespace Src\Model\DTO\Read;

class UserSocialReadDto
{
    public int $id;
    public int $user_id;
    public ?string $Instagram;
    public ?string $TikTok;
    public ?string $Facebook;
    public ?string $YouTube;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->user_id = $array['user_id'];
        $this->Instagram = $array['Instagram'];
        $this->TikTok = $array['TikTok'];
        $this->Facebook = $array['Facebook'];
        $this->YouTube = $array['YouTube'];
    }
}
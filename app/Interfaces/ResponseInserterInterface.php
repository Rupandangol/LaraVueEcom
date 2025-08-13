<?php

namespace App\Interfaces;

use App\DTOs\AiReponseDto;

interface ResponseInserterInterface
{
    public function insert(AiReponseDto $data);
}

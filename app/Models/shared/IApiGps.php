<?php

namespace App\Models\shared;

interface IApiGps
{
    public function getTrackPoint(): array;
}

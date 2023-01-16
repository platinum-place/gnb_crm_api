<?php

namespace App\Models\shared;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

abstract class AuthModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
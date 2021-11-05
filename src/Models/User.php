<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Traits\UserPropertiesTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

abstract class User extends Authenticatable implements MustVerifyEmail, EvaUserInterface
{
    use UserPropertiesTrait, HasFactory, HasApiTokens;
}

<?php

namespace App\Entity\Enums;

enum UserStatus: string
{
    case Admin = 'admin';
    case Employee = 'employee';
    case Client = 'client';
}
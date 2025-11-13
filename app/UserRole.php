<?php

namespace App;

enum UserRole: string
{
    case Normal = 'normal';
    case Manager = 'manager';
    case Admin = 'Admin';
}

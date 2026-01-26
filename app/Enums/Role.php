<?php

namespace App\Enums;

enum Role: string {
    case Admin = 'admin';
    case Lead = 'lead';
    case Member = 'member';
}

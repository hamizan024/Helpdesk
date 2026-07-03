<?php

namespace App\Enums;

enum ActivityAction: string
{
    case Create  = 'create';
    case Assign  = 'assign';
    case Status  = 'status';
    case Comment = 'comment';
}

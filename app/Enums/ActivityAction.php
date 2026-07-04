<?php

namespace App\Enums;

/**
 * Represents the type of action recorded in a ticket's activity log.
 */
enum ActivityAction: string
{
    case Create  = 'create';
    case Assign  = 'assign';
    case Status  = 'status';
    case Comment = 'comment';
}

<?php

namespace App\Enums;

enum TaskStatus: string
{
    case BACKLOG = 'backlog';
    case TODO = 'todo';
    case DOING = 'doing';
    case REVIEW = 'review';
    case DONE = 'done';
}

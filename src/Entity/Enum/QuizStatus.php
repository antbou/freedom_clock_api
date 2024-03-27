<?php

namespace App\Entity\Enum;

enum QuizStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}

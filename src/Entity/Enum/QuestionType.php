<?php

namespace App\Entity\Enum;

enum QuestionType: string
{
    case MULTIPLE_CHOICE = 'multiple_choice';
    case TEXT = 'text';
    case SINGLE_CHOICE = 'single_choice';
    case TRUE_FALSE = 'true_false';
}

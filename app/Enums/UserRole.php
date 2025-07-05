<?php
enum UserRole: string
{
    case STUDENT = 'student';
    
    case LIBRARIAN = 'librarian';

    case ADMIN = 'admin';

    public static function caseArray(): array
    {
        return array_column(UserRole::cases(), 'value');
    }

}

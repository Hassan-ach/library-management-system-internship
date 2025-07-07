<?php
enum RequestStatus: string
{
case PENDING = 'pending';

case APPROVED = 'approved';

case REJECTED = 'rejected';

case BORROWED = 'borrowed';

case RETURNED = 'returned';

case OVERDUE = 'overdue';

case CANCELED = 'canceled';

    public static function caseArray(): array
    {
        return array_column(RequestStatus::cases(), 'value');
    }

}


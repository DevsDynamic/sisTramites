<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case DRAFT = 'draft';

    case PENDING = 'pending';

    case DERIVED = 'derived';

    case APPROVED = 'approved';

    case OBSERVED = 'observed';

    case REJECTED = 'rejected';

    case SIGNED = 'signed';

    case ARCHIVED = 'archived';
}
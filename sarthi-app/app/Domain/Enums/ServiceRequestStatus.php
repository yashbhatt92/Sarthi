<?php

namespace App\Domain\Enums;

enum ServiceRequestStatus: string
{
    case Pending = 'pending';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case NeedsInfo = 'needs_info';
    case Reviewing = 'reviewing';
    case Completed = 'completed';
    case FlaggedRevoke = 'flagged_revoke';

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, match ($this) {
            self::Pending => [self::Assigned, self::FlaggedRevoke],
            self::Assigned => [self::InProgress, self::NeedsInfo, self::FlaggedRevoke],
            self::InProgress => [self::NeedsInfo, self::Reviewing, self::Completed],
            self::NeedsInfo => [self::InProgress, self::Reviewing],
            self::Reviewing => [self::Completed, self::NeedsInfo],
            self::Completed => [],
            self::FlaggedRevoke => [],
        }, true);
    }
}

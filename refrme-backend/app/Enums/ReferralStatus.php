<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 13:17
 * ReferralType.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

namespace App\Enums;

/**
 * Enum representing valid referral statuses.
 */
enum ReferralStatus: string
{
    case New = 'new';
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
}

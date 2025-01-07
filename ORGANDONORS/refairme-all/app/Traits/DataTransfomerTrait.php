<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 07.01.2025, 22:11
 * DataTransfomerTrait.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

trait DataTransfomerTrait
{
    /**
     * @param array $weightsOfDb
     * @return array
     *
     * Transforms keyed job/profile weight values
     * into raw data series
     */
    public function transformWeight(array $weightsOfDb): array
    {
        return array_values($weightsOfDb);
    }

}
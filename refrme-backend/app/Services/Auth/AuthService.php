<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 18:27
 * Auth.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

namespace App\Services\Auth;

use App\Models\User;
use Monolog\Logger;
use PDO;
use SlimSession\Helper as Session;

class AuthService
{
    protected $db;
    protected $session;
    protected $logger;

    public function __construct(PDO $db, Session $session, Logger $logger)
    {
        $this->db = $db;
        $this->session = $session;
        $this->logger=$logger;

        $this->logger->debug("Debug Message");
    }

    public function attempt(string $email, string $password): bool
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            $this->session->set('user', $user->id);
            return true;
        }

        return false;
    }

    public function check(): bool
    {
        return $this->session->exists('user');
    }

    public function user()
    {
        if ($this->check()) {
            return User::find($this->session->get('user'));
        }
        return null;
    }

    public function logout(): void
    {
        $this->session->delete('user');
    }
}
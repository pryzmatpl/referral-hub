<?php
/*
 * Copyright (c) 2024 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 14.01.2025, 21:32
 * UserService.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

namespace App\Services;

use App\Models\User;
use RuntimeException;
use Ramsey\Uuid\Uuid;

final class UserService
{
    public function __construct(
        private readonly EmailService $emailService
    ) {}

    public function createUser(array $payload): User
    {
        $email = $payload['email'] ?? throw new RuntimeException('Email is required');
        $password = $payload['password'] ?? throw new RuntimeException('Password is required');
        $role = $payload['role'] ?? throw new RuntimeException('Role is required');

        $user = User::create([
            'email' => $email,
            'name' => $this->generateAccountName($email),
            'first_name' => $payload['firstname'] ?? '',
            'last_name' => $payload['lastname'] ?? '',
            'password' => $this->hashPassword($password),
            'activ_code' => $this->generateActivationCode(),
            'group_id' => $payload['chosenGroup'] ?? null,
            'cvadded' => false,
            'current_role' => $role,
            'unique_id' => $payload['uniqueId'] ?? Uuid::uuid4()->toString(),
        ]);

        if (!$user) {
            throw new RuntimeException('Failed to create user');
        }

        // Optionally send confirmation email
        // $this->sendConfirmationEmail($user, $role);

        return $user;
    }

    public function getUserRoles(User $user): array
    {
        return [
            'developer' => $user->is_developer,
            'admin' => $user->is_admin,
            'recruiter' => $user->is_recruiter,
            'candidate' => $user->is_candidate,
        ];
    }

    public function activateUser(string $activationCode): User
    {
        $user = User::where('activ_code', $activationCode)->first();

        if (!$user) {
            throw new RuntimeException('Invalid activation code');
        }

        if ($user->activ === 1) {
            throw new RuntimeException('User is already activated');
        }

        $user->activ = 1;
        $user->save();

        return $user;
    }

    public function sendConfirmationEmail(User $user, string $role): void
    {
        $activationLink = $this->generateActivationLink($user->activ_code);

        $this->emailService->sendEmail(
            to: $user->email,
            subject: 'Please confirm your email from Refair.me',
            template: 'signup',
            data: [
                'role' => $role,
                'link' => $activationLink,
            ]
        );
    }

    private function generateAccountName(string $email): string
    {
        $parts = explode('@', $email);
        return $parts[0] . $parts[1] . md5((string) time());
    }

    private function generateActivationCode(): string
    {
        return urlencode(Uuid::uuid4()->toString());
    }

    private function hashPassword(string $password): string
    {
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

        if ($hashedPassword === false) {
            throw new RuntimeException('Failed to hash password');
        }

        return $hashedPassword;
    }

    private function generateActivationLink(string $activationCode): string
    {
        return sprintf(
            '%s/auth/confirm?code=%s',
            $_ENV['FRONTEND_URL'] ?? throw new RuntimeException('FRONTEND_URL not configured'),
            urlencode($activationCode)
        );
    }
}
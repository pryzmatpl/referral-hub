<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 18:00
 * UserServiceCest.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */

namespace Tests\Functional;

use App\Models\User;
use App\Services\EmailService;
use App\Services\UserService;
use Codeception\Test\Unit;
use RuntimeException;
use Mockery;
use Mockery\MockInterface;
use Tests\Support\FunctionalTester;

class UserServiceTest extends Unit
{
    protected FunctionalTester $tester;

    /**
     * @var EmailService|MockInterface
     */
    private $emailServiceMock;

    /**
     * @var UserService
     */
    private $userService;

    protected function _before()
    {
        $this->emailServiceMock = Mockery::mock(EmailService::class);
        $this->userService = new UserService($this->emailServiceMock);

        // Set environment variable for tests
        $_ENV['FRONTEND_URL'] = 'https://example.com';
    }

    protected function _after()
    {
        Mockery::close();
        unset($_ENV['FRONTEND_URL']);
    }

    public function testCreateUserWithValidPayload()
    {
        // Arrange
        $payload = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'developer',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'chosenGroup' => 1,
            'uniqueId' => '123e4567-e89b-12d3-a456-426614174000'
        ];

        $this->tester->haveMethod(User::class, 'create', function ($data) use ($payload) {
            $user = new User();
            $user->email = $payload['email'];
            $user->name = 'test@example.comexample.com' . md5(time()); // approximate match
            $user->first_name = $payload['firstName'];
            $user->last_name = $payload['lastName'];
            $user->current_role = $payload['role'];
            $user->group_id = $payload['chosenGroup'];
            $user->unique_id = $payload['uniqueId'];
            $user->cvadded = false;

            return $user;
        });

        // Act
        $result = $this->userService->createUser($payload);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($payload['email'], $result->email);
        $this->assertEquals($payload['firstName'], $result->first_name);
        $this->assertEquals($payload['lastName'], $result->last_name);
        $this->assertEquals($payload['role'], $result->current_role);
        $this->assertEquals($payload['chosenGroup'], $result->group_id);
        $this->assertEquals($payload['uniqueId'], $result->unique_id);
        $this->assertFalse($result->cvadded);
    }

    public function testCreateUserGeneratesUniqueIdWhenNotProvided()
    {
        // Arrange
        $payload = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'developer',
        ];

        $this->tester->haveMethod(User::class, 'create', function ($data) use ($payload) {
            $this->assertArrayHasKey('unique_id', $data);
            $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $data['unique_id']);

            $user = new User();
            $user->email = $payload['email'];
            $user->current_role = $payload['role'];
            $user->unique_id = $data['unique_id'];

            return $user;
        });

        // Act
        $result = $this->userService->createUser($payload);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $result->unique_id);
    }

    public function testCreateUserWithMissingEmail()
    {
        // Arrange
        $payload = [
            'password' => 'password123',
            'role' => 'developer',
        ];

        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Email is required');

        $this->userService->createUser($payload);
    }

    public function testCreateUserWithMissingPassword()
    {
        // Arrange
        $payload = [
            'email' => 'test@example.com',
            'role' => 'developer',
        ];

        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Password is required');

        $this->userService->createUser($payload);
    }

    public function testCreateUserWithMissingRole()
    {
        // Arrange
        $payload = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Role is required');

        $this->userService->createUser($payload);
    }

    public function testCreateUserFailure()
    {
        // Arrange
        $payload = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'developer',
        ];

        $this->tester->haveMethod(User::class, 'create', function ($data) {
            return null; // Simulate failure
        });

        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to create user');

        $this->userService->createUser($payload);
    }

    public function testGetUserRoles()
    {
        // Arrange
        $user = new User();
        $user->is_developer = true;
        $user->is_admin = false;
        $user->is_recruiter = true;
        $user->is_candidate = false;

        // Act
        $roles = $this->userService->getUserRoles($user);

        // Assert
        $this->assertEquals([
            'developer' => true,
            'admin' => false,
            'recruiter' => true,
            'candidate' => false,
        ], $roles);
    }

    public function testActivateUserSuccess()
    {
        // Arrange
        $activationCode = 'test-activation-code';
        $user = new User();
        $user->activ = 0;
        $user->activ_code = $activationCode;

        $this->tester->haveMethod(User::class, 'where', function ($field, $value) use ($activationCode) {
            $this->assertEquals('activ_code', $field);
            $this->assertEquals($activationCode, $value);

            $mock = Mockery::mock();
            $mock->shouldReceive('first')->andReturn($user);
            return $mock;
        });

        // Mock the save method on the user
        $user = $this->getMockBuilder(User::class)
            ->onlyMethods(['save'])
            ->getMock();
        $user->activ = 0;
        $user->activ_code = $activationCode;
        $user->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $this->tester->haveMethod(User::class, 'where', function ($field, $value) use ($user) {
            $mock = Mockery::mock();
            $mock->shouldReceive('first')->andReturn($user);
            return $mock;
        });

        // Act
        $result = $this->userService->activateUser($activationCode);

        // Assert
        $this->assertEquals(1, $result->activ);
    }

    public function testActivateUserWithInvalidCode()
    {
        // Arrange
        $activationCode = 'invalid-code';

        $this->tester->haveMethod(User::class, 'where', function ($field, $value) {
            $mock = Mockery::mock();
            $mock->shouldReceive('first')->andReturn(null);
            return $mock;
        });

        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid activation code');

        $this->userService->activateUser($activationCode);
    }

    public function testActivateUserAlreadyActivated()
    {
        // Arrange
        $activationCode = 'test-activation-code';
        $user = new User();
        $user->activ = 1;
        $user->activ_code = $activationCode;

        $this->tester->haveMethod(User::class, 'where', function ($field, $value) use ($user) {
            $mock = Mockery::mock();
            $mock->shouldReceive('first')->andReturn($user);
            return $mock;
        });

        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('User is already activated');

        $this->userService->activateUser($activationCode);
    }

    public function testSendConfirmationEmail()
    {
        // Arrange
        $user = new User();
        $user->email = 'test@example.com';
        $user->activ_code = 'test-activation-code';
        $role = 'developer';

        $this->emailServiceMock
            ->shouldReceive('sendEmail')
            ->once()
            ->with(
                'test@example.com',
                'Please confirm your email from Refair.me',
                'signup',
                Mockery::on(function ($data) {
                    return $data['role'] === 'developer' &&
                        strpos($data['link'], 'https://example.com/auth/confirm?code=') === 0;
                })
            );

        // Act
        $this->userService->sendConfirmationEmail($user, $role);

        // No assert needed as we're verifying the mock expectation
    }

    public function testGenerateActivationLinkThrowsExceptionWhenFrontendUrlNotConfigured()
    {
        // Arrange
        unset($_ENV['FRONTEND_URL']);
        $user = new User();
        $user->email = 'test@example.com';
        $user->activ_code = 'test-activation-code';
        $role = 'developer';

        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('FRONTEND_URL not configured');

        $this->userService->sendConfirmationEmail($user, $role);
    }
}
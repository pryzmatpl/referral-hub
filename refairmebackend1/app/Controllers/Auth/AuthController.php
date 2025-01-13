<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 13.01.2025, 21:16
 * AuthController.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Http\HttpCodes;
use App\Validation\Validator;
use Exception;
use Monolog\Logger;
use Nette\Mail\Mailer;
use App\Models\User;
use App\Controllers\Controller;
use Litipk\Jiffy\UniversalTimestamp;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{
    protected Auth $auth;
    protected Validator $validator;
    protected Mailer $mailer;
    protected Logger $logger;

    public function __construct(
        Auth $auth,
        Validator $validator,
        Mailer $mailer,
        Logger $logger,
    )
    {
        $this->auth = $auth;
        $this->validator = $validator;
        $this->mailer = $mailer;
        $this->logger =$logger;
    }

    public function getSignOut(Request $request, Response $response)
    {
        $this->auth->logout();

        $response->getBody()->write(json_encode([
            'state' => 'success',
            'message' => 'You have been logged out'
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function getSignIn($request, $response)
    {
        $this->flash->addMessage('messages', 'Hello, please login.');

        return $this->view->render($response, 'app-boot.twig', [
            'state' => json_encode('help'),
            'messages' => json_encode($this->flash->getMessages(), true)
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function postSignIn(Request $request, Response $response): mixed
    {
        try {
            $payload = json_decode($request->getBody(), true);
            $params = (array)$payload['params'];

            $user = User::where('unique_id', $params['uniqueId'])->first();

            if (!$user) {
                $response->getBody()->write(json_encode([
                    'state' => 'user not found',
                    'auth' => false
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json');
            }

            $roles = [
                'developer' => $user->is_developer,
                'admin' => $user->is_admin,
                'recruiter' => $user->is_recruiter,
                'candidate' => $user->is_candidate
            ];

            $_SESSION['user'] = $user;
            $_SESSION['user_roles'] = $roles;

            $response->getBody()->write(json_encode([
                'state' => 'success',
                'auth' => true
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function buildCornerstone(User $user, string $email, string $roles): string
    {
        $origin = env('HASH_BASE');
        $now = UniversalTimestamp::now();

        $cornerstone = $this->iwahash($origin, "TIMESTAMP", $now);
        $cornerstone = $this->iwahash($cornerstone, "ORIGIN", $origin);
        $cornerstone = $this->iwahash($cornerstone, "EMAIL", $email);
        $cornerstone = $this->iwahash($cornerstone, "AUTH", "TRUE");
        $cornerstone = $this->iwahash($cornerstone, "ROLES", $roles);
        $cornerstone = $this->iwahash($cornerstone, "CURRENT_ROLE", $user->current_role);

        return $cornerstone;
    }

    public function getSignUp($request, $response)
    {
        try {
            $origin = env('HASH_BASE');
            $now = UniversalTimestamp::now();

            $cornerstone = $this->iwahash($origin, "TIMESTAMP", $now);
            $cornerstone = $this->iwahash($cornerstone, "ORIGIN", $origin);
            $cornerstone = $this->iwahash($cornerstone, "SESSION_AUTH", "false");

            return $response->withJson([
                'planck' => $cornerstone,
                'dehashed' => $this->dehash($cornerstone)
            ]);
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postSignUp($request, $response)
    {
        try {
            if (!$this->validateSignUpRequest($request)) {
                $response->getBody()->write(json_encode([
                    'state' => 'error',
                    'message' => 'Validation Failed - Email taken or in invalid format.'
                    ]));

                return $response
                    ->withHeader('Content-Type', 'application/json');
            }

            $user = $this->createUser($request);
            /* $this->sendConfirmationEmail($user, $request->$payload['role']); */

            $response->getBody()->write(json_encode([
                'message' => 'Successful Registration!',
                'state' => 'Success'
                ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
                ]));
                
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }

    private function validateSignUpRequest($request): bool
    {
        $validation = $this->validator->validate($request, [
            /* 'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'password' => v::noWhitespace()->notEmpty(),
            'role' => v::notEmpty()->in(['recruiter', 'candidate']) */
        ]);

        return !$validation->failed();
    }

    function uuidv4()
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function createUser($request): User
    {
        $payload = json_decode($request->getBody(), true);
        $email = $payload['email'];
        $uniqueId = $this->uuidv4();
        $accountName = $this->generateAccountName($email);
        $role = $payload['role'];

        $user = User::create([
            'email' => $email,
            'name' => $accountName,
            'first_name' => $payload['firstname'],
            'last_name' => $payload['lastname'],
            'password' => password_hash($payload['password'], PASSWORD_DEFAULT),
            'activ_code' => urlencode($uniqueId),
            'group_id' => $payload['chosenGroup'],
            'cvadded' => false,
            'current_role' => $role,
            'unique_id' => $payload['uniqueId']
        ]);

        $user->save();

        return $user;
    }

    private function generateAccountName(string $email): string
    {
        $parts = explode('@', $email);
        return $parts[0] . $parts[1] . md5(date('YMDS'));
    }

    private function sendConfirmationEmail(User $user, string $role): void
    {
       /*  $activationLink = env('FRONTEND_URL') . "auth/confirm?code=" . urlencode($user->activ_code);

        $mail = new Message;
        $mail->setFrom(env('MAIL_FROM'))
            ->addTo($user->email)
            ->setSubject('Please confirm your email from Refair.me')
            ->setHTMLBody(renderEmailTemplate('signup', [
                'role' => $role,
                'link' => $activationLink
            ]));

        $this->mailer->send($mail); */
    }

    public function confirmEmail($request, $response)
    {
        try {
            if (!$request->getParam('code')) {
                return $response->withJson([
                    'message' => 'No activation code available!'
                ]);
            }

            $user = User::where('activ_code', $request->getParam('code'))->first();

            if ($user->activ == 1) {
                $this->flash->addMessage('info', 'This user had already been activated.');
                return $this->response->withRedirect($this->router->pathFor('auth.signin'));
            }

            $user->activ = 1;
            $user->save();

            return $response->withJson([
                'message' => 'Your signup is a success. You can sign in now! Please return to your application window.'
            ]);
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
        }
    }

}

<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Validation\Validator;
use Exception;
use Monolog\Logger;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use Litipk\Jiffy\UniversalTimestamp;
use Psr\Container\ContainerInterface;
use Slim\Csrf\Guard;
use SlimSession\Helper;

class AuthController extends Controller
{
    protected $auth;
    protected $validator;
    protected $mailer;
    protected $logger;

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

    private const ORIGIN = "prizm";
    private const SEPARATOR = "~";
    private const CHILDSEPARATOR = ":";

    public function getSignOut($request, $response)
    {
        $this->auth->logout();
        return $response->withJson([
            'state' => 'success',
            'message' => 'You have been logged out'
        ]);
    }

    public function getSignIn($request, $response)
    {
        $this->flash->addMessage('messages', 'Hello, please login.');

        return $this->view->render($response, 'app-boot.twig', [
            'state' => json_encode('help'),
            'messages' => json_encode($this->flash->getMessages(), true)
        ]);
    }

    public function postSignIn($request, $response)
    {
        try {
            $payload = $request->getQueryParams();
            $auth = $this->auth->attempt($payload['email'], $payload['password']);

            if (!$auth) {
                return $response->withJson([
                    'message' => "Validation failed - you stated a malformed email address or a wrong password for {$payload['email']}",
                    'state' => 'error',
                    'auth' => false
                ]);
            }

            $user = User::where('email', $payload['email'])->first();

            $roles = [
                'developer' => $user->is_developer,
                'admin' => $user->is_admin,
                'recruiter' => $user->is_recruiter,
                'candidate' => $user->is_candidate
            ];

            $cornerstone = $this->buildCornerstone($user, $payload['email'], json_encode($roles, true));

            $_SESSION['creds'][urlencode($cornerstone)] = [
                'token' => $cornerstone,
                'user' => $payload['email']
            ];
            $_SESSION['user'] = $user;

            return $response->withJson([
                'planck' => $cornerstone,
                'state' => 'success',
                'auth' => true
            ]);
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
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
                return $response->withJson([
                    'state' => 'error',
                    'message' => 'Validation Failed - Email taken or in invalid format.'
                ]);
            }

            $user = $this->createUser($request);
            $this->sendConfirmationEmail($user, $request->getParam('role'));

            return $response->withJson([
                'message' => 'Successful Registration!',
                'state' => 'Success'
            ]);
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function validateSignUpRequest($request): bool
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'password' => v::noWhitespace()->notEmpty(),
            'role' => v::notEmpty()->in(['recruiter', 'candidate'])
        ]);

        return !$validation->failed();
    }

    private function createUser($request): User
    {
        $email = $request->getParam('email');
        $uniqueId = $this->generateUniqueId($email);
        $accountName = $this->generateAccountName($email);

        $user = User::create([
            'email' => $email,
            'name' => $accountName,
            'first_name' => $request->getParam('firstname'),
            'last_name' => $request->getParam('lastname'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
            'activ_code' => urlencode($uniqueId),
            'group_id' => $request->getParam('chosengroup'),
            'cvadded' => false,
        ]);

        $role = strtolower($request->getParam('role'));
        $user->current_role = $role;
        $user->{"is_$role"} = true;
        $user->save();

        return $user;
    }

    private function generateUniqueId(string $email): string
    {
        $uniqueId = self::ORIGIN;
        $uniqueId = $this->iwahash($uniqueId, "UMAIL", $email);
        $uniqueId = $this->iwahash($uniqueId, "TOKEN", env('TOKEN'));
        $uniqueId = $this->iwahash($uniqueId, "DATE", date('Ymdhis'));

        return $uniqueId;
    }

    private function generateAccountName(string $email): string
    {
        $parts = explode('@', $email);
        return $parts[0] . $parts[1] . md5(date('YMDS'));
    }

    private function sendConfirmationEmail(User $user, string $role): void
    {
        $activationLink = env('FRONTEND_URL') . "auth/confirm?code=" . urlencode($user->activ_code);

        $mail = new Message;
        $mail->setFrom(env('MAIL_FROM'))
            ->addTo($user->email)
            ->setSubject('Please confirm your email from Refair.me')
            ->setHTMLBody(renderEmailTemplate('signup', [
                'role' => $role,
                'link' => $activationLink
            ]));

        if (!$this->mailer->send($mail)) {
            throw new Exception("Mailer could not send Emails");
        }
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

    public function csrftoken($request, $response)
    {
        try {
            $session = new Helper;

            if (isset($session['csrf_keypair'])) {
                return $response->withJson($session['csrf_keypair']);
            }

            $slimGuard = new Guard;
            $keyPair = $slimGuard->generateToken();
            $session['csrf_keypair'] = $keyPair;

            return $response->withJson($keyPair);
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
        }
    }
}

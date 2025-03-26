<?php
namespace App\Controllers;
use Exception;
use Nette\Mail\Message;
use Respect\Validation\Validator as v;
use App\Models\Referral;
use App\Models\ReferralState;
use App\Models\User;
use Requests;
use Slim\Http\Request;
use Slim\Csrf\Guard;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Illuminate\Database\Capsule\Manager as DB;
use SlimSession\Helper as Session;
use App\Models\JobDesc;
use App\Models\Company;
use App\Services\EmailService;

class ReferralController extends Controller
{
    private EmailService $emailService;
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    public function get($request, $response, $args){
        return $response->withJson(cc(array('1')));
    }

    public function getReferralSend($request, $response, $args) {
        try {
            $email = strip_tags($args['user']);
            $user = User::where('email', $email)->first();

            if(empty($user))
                throw new Exception('User not found.');

            $referrals = Referral::where('users_id', $user->id)
                ->with('User')
                ->with('Job')
                ->get();

            return $response->withJson(cc($referrals->toArray()));
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function getReferralReceived($request, $response, $args) {
        try {
            $email = strip_tags($args['email']);
            $referrals = Referral::where('email', $email)
                ->with('User')
                ->with('Job')
                ->get();

            return $response->withJson(cc($referrals->toArray()));
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function add($request, $response, $args){
        try {
            $data = json_decode($request->getBody(), true);
            $userId = $_SESSION['user']->id ?? null;

            $data['job_id'] = !empty($data['job_id']) ? intval($data['job_id']) : null;
            $data['user_id'] = !empty($data['user_id']) ? intval($data['user_id']) : null;
            $data['email'] = !empty($data['email']) ? strip_tags($data['email']) : null;
            $data['name'] = !empty($data['name']) ? strip_tags($data['name']) : null;

            $referral = Referral::firstOrCreate(
                [
                    'jobid' => $data['job_id'],
                    'email' => $data['email']
                ],
                [
                    'referrer_id' => $userId,
                    'name' => $data['name']
                ]
            );

            $referral->save();

            $res = [
                'status' => "success",
                'job' => $referral,
                'message' => "Successfully referred"
            ];

            if($this->isProposalRejected($data)) {
                $res = [
                    'message'=> "The person has already rejected this job",
                    'status' => "failed",
                    'data'=> $data
                ];
                return $this->jsonResponse($response, $res);
            }

            $this->sendEmailAction($data);

            return $this->jsonResponse($response, $res);
            
            ////////////// TODO //////////////////////////

/*
            if($referral->save()) {
                $referral->update(['hash' => $this->iwahash($referral->id, "TOKEN", env('TOKEN'))]);

                $user = User::where('id', $userId)->first();
                $referral->user = $user;

                $job = JobDesc::where('id', $data['job_id'])->first();
                $referral->job = $job;

                $company = Company::where('id', $job->companyId)->first();
                $referral->company = $company;

                if($this->sendMailJob($referral) && $this->sendMailConfirmation($referral)) {
                    $referral->update(['status' => Referral::VALUE_STATUS_PENDING]);

                    $referralState = new ReferralState();
                    $referralState->jobs_referral_id = $referral->id;
                    $referralState->state = 'email_sent';
                    $referralState->comment = 'Email from '.$referral->user->email.' with a job proposal has been sent.';
                    $referralState->save();

                    return $response->withJson([
                        'message'=> "Successfully added referral for job #" . $data['job_id'],
                        'status' => "success",
                        'referral'=> cc($referral->toArray())
                    ]);
                }
            }

            return $response->withJson([
                'message'=> "There was an error sending your referral",
                'status' => "error",
                'referral'=> cc($referral->toArray())
            ]); */

        } catch (Exception $e) {
            $error = ['error' => $e->getMessage()];
            return $this->jsonResponse($response, $error, 500);
        }
    }

    public function delete($request, $response, $args) {}

    public function update($request, $response, $args) {}

    private function isProposalRejected($data) {
        $isRejected = false;

        $arrReferral = Referral::where([
            ['jobid', '=', $data['job_id']],
            ['email', '=', $data['email']],
        ])->get();

        foreach($arrReferral as $referral) {
            if($referral->state == Referral::STATUS_REJECTED)
                $isRejected = true;
        }

        return $isRejected;
    }

    /////////////// TODO ////////////////////////
    public function sendEmailAction($data): bool
    {
        $to = $data['email'];
        $subject = 'New job proposal from ' . $data['user_email'];
        $template = 'referral_job';
        /* $data = [
            'user_email' => 'user@example.com',
            'email' => 'referred@example.com',
            'company' => 'Awesome Inc.',
            'job' => 'Software Engineer'
        ]; */
        
        $attachments = [];
        $cc = ['cc@example.com'];
        $bcc = ['bcc@example.com'];

        try {
            $this->emailService->sendEmail($to, $subject, $template, $data, $attachments, $cc, $bcc);
            return true;
        } catch (\RuntimeException $e) {
            // Log the error or handle the exception as needed
            return false;
        }
    }

    private function sendMailJob($params) {
        $mail = new Message;

        $mail
            ->setFrom(env('MAIL_USERNAME'))
            ->addTo($params->email)
            ->setSubject('You received a new job offer')
            ->setHTMLBody(renderEmailTemplate('referral_job', array(
                'user_email' => $params->user->email,
                'user_name' => $params->user->name,
                'email' => $params->email,
                'company' => $params->company->name,
                'job' => $params->job->title,
            )));

        return !$this->mailer->send($mail);
    }

    private function sendMailConfirmation($params) {
        $mail = new Message;

        $mail
            ->setFrom(env('MAIL_USERNAME'))
            ->addTo($params->email)
            ->setSubject('You referred a person to work as '.$params->job->title)
            ->setHTMLBody(renderEmailTemplate('referral_confirmation', array(
                'user_email' => $params->user->email,
                'user_name' => $params->user->name,
                'email' => $params->email,
                'company' => $params->company->name,
                'job' => $params->job->title,
            )));

        return !$this->mailer->send($mail);
    }
}

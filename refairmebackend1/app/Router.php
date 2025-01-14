<?php
/**
 * This code is part of referalhub
 * 2024 08 08 18 34
 */
namespace App;
use App\Controllers\AppointmentsController;
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\PasswordController;
use App\Controllers\ConsoleController;
use App\Controllers\JobController;
use App\Controllers\ProfileController;
use App\Controllers\ProjectController;
use App\Controllers\RefairController;
use App\Controllers\ReferralController;
use App\Controllers\UserController;
use Slim\App;

class Router {
    /**
     * @param App $app
     * @return void
     * Register all routes in app instance
     */
    static public function registerRoutes(App &$app) : void {
        $app->get('/', [RefairController::class, 'index'])->setName('home');
        $app->get('/eval', [RefairController::class, 'evalkeywords'])->setName('refair.eval');

        $app->get('/csrftoken', [AuthController::class, 'csrftoken'])->setName('refair.token');
        $app->get('/jwt', [AuthController::class, 'jwttoken'])->setName('refair.jwttoken');

        $app->get('/auth/signup', [AuthController::class, 'getSignUp'])->setName('auth.signup');
        $app->post('/auth/signup', [AuthController::class, 'signUp']);
        $app->get('/auth/confirm', [AuthController::class, 'confirmEmail'])->setName('auth.confirm');
        $app->post('/auth/signin', [AuthController::class, 'signIn'])->setName('auth.signin');
        $app->get('/auth/recover', [AuthController::class, 'getChangePass'])->setName('auth.recover');
        $app->get('/auth/recover/{hash}', [AuthController::class, 'getChangePass']);
        $app->post('/auth/recover', [AuthController::class, 'postChangePass']);
        $app->post('/auth/change', [AuthController::class, 'changePass'])->setName('auth.pwdxchng');
        $app->get('/api/auth/signout', [AuthController::class, 'getSignOut'])->setName('auth.signout');
        $app->post('/auth/signin/linkedaccess', [AuthController::class, 'getLinkedInAccessToken']);
        $app->post('/auth/signin/linkedinfo', [AuthController::class, 'getLinkedInUserInfo']);

        $app->get('/api/job/{id}', [RefairController::class, 'getjob'])->setName('refair.job.get');
        $app->get('/matchprofile', [RefairController::class, 'matchprofile'])->setName('refair.matchprofile');
        $app->get('/matchjob/{id}', [RefairController::class, 'matchjob'])->setName('refair.matchjob');
        $app->post('/api/user/storeprofile', [RefairController::class, 'storeprofile'])->setName('refair.user.storeprofile');
        $app->post('/api/user/getprofile/{id}', [RefairController::class, 'getprofile'])->setName('refair.user.getprofile');

        $app->post('/project/add', [ProjectController::class, 'add'])->setName('refair.add.project');
        $app->post('/project/update/{id}', [ProjectController::class, 'update'])->setName('refair.update.project');
        $app->post('/project/delete/{id}', [ProjectController::class, 'deleteProject'])->setName('refair.delete.project');
        $app->get('/project/get/all', [ProjectController::class, 'getProjects'])->setName('refair.get.projects');
        $app->get('/project/get/all/{company}', [ProjectController::class, 'getCompanyProjects'])->setName('refair.get.company.project');
        $app->get('/project/get/{id}', [ProjectController::class, 'getProject'])->setName('refair.get.project');

        $app->post('/api/apply', [JobController::class, 'apply'])->setName('refair.apply');
        $app->get('/getapply/{user}', [JobController::class, 'getApply'])->setName('refair.apply.user');
        $app->get('/getjobs', [JobController::class, 'get'])->setName('refair.getjobs');
        $app->get('/getjobs/{page}', [JobController::class, 'get'])->setName('refair.getjobs');
        $app->post('/getjobs', [JobController::class, 'get'])->setName('refair.getjobs');
        $app->post('/job/add', [JobController::class, 'add'])->setName('refair.add.job');
        $app->post('/api/job/new', [JobController::class, 'add'])->setName('refair.add.new.job');
        $app->post('/job/update/{id}', [JobController::class, 'update'])->setName('refair.update.job');
        $app->get('/job/delete/{id}', [JobController::class, 'delete'])->setName('refair.delete.job');

        $app->post('/referral/add', [ReferralController::class, 'add'])->setName('refair.add.referral');
        $app->get('/referral/all', [ReferralController::class, 'getall'])->setName('refair.all.referral');
        $app->get('/referral/get/{id}', [ReferralController::class, 'get'])->setName('refair.get.referral');
        $app->get('/referral/delete/{id}', [ReferralController::class, 'delete'])->setName('refair.delete.referral');
        $app->post('/referral/update/{id}', [ReferralController::class, 'update'])->setName('refair.update.referral');
        $app->get('/getreferral/send/{user}', [ReferralController::class, 'getReferralSend'])->setName('refair.getreferral.send');
        $app->get('/getreferral/received/{email}', [ReferralController::class, 'getReferralReceived'])->setName('refair.getreferral.received');
        $app->post('/upload', [RefairController::class, 'uploadFile']);
        $app->get('/matchjobs/strong/{uid}', [RefairController::class, 'jobsToStrongUid'])->setName('refair.match.job.strong');
        $app->get('/matchprofiles/strong/{jid}', [RefairController::class, 'profilesToStrongJid'])->setName('refair.match.profile.strong');

        $app->get('/user/get', [UserController::class, 'get']);
        $app->post('/user/update', [UserController::class, 'update']);

        $app->get('/api/auth/password/change', [PasswordController::class, 'getChangePassword'])->setName('auth.password.change');
        $app->post('/api/auth/password/change', [PasswordController::class, 'postChangePassword']);
        $app->get('/api/auth/password/recoverlink', [PasswordController::class, 'recoverLink']);
        $app->post('/api/auth/password/recover', [PasswordController::class, 'recover']);

        $app->get('/company/get/all', [RefairController::class, 'getCompanies'])->setName('refair.get.companies');
        $app->get('/company/get/{id}', [RefairController::class, 'getCompany'])->setName('refair.get.company');


        $app->group('', function () use ($app) {
            $app->get('/landing', [AuthController::class, 'getSignUpLanding'])->setName('auth.landing');

            $app->get('/processoauth', [RefairController::class, 'processoauth'])->setName('oauth2.linkedin');
            $app->post('/processoauth', [RefairController::class, 'processoauth']);
            $app->get('/profilebuild', [RefairController::class, 'getBuildProfile'])->setName('profile.build');
        });

        $app->group('', function () use ($app) {
            $app->get('/profile', [ProfileController::class, 'index'])->setName('profile.dashboard');
            $app->post('/profile', [ProfileController::class, 'index']);
            $app->get('/account', [ProfileController::class, 'account'])->setName('account');
            $app->post('/account', [ProfileController::class, 'account']);

            $app->get('/appointments/get', [AppointmentsController::class, 'get'])->setName('appointment.get');
            $app->post('/appointments/add', [AppointmentsController::class, 'create'])->setName('appointment.create');
            $app->post('/appointments/delete/{id}', [AppointmentsController::class, 'delete'])->setName('appointment.delete');
            $app->post('/appointments/update/{id}', [AppointmentsController::class, 'update'])->setName('appointment.update');

            $app->get('/getlocations', [RefairController::class, 'getlocs'])->setName('refair.getlocations');
            $app->post('/company/add', [RefairController::class, 'addCompany'])->setName('refair.add.company');
            $app->post('/company/update/{id}', [RefairController::class, 'updateCompany'])->setName('refair.update.company');
            $app->post('/company/delete/{id}', [RefairController::class, 'deleteCompany'])->setName('refair.delete.company');
        });

        $app->get('/cli', [ConsoleController::class, 'runCommand']);
    }
}
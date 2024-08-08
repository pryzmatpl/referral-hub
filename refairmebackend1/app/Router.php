<?php
/**
 * This code is part of referalhub
 * 2024 08 08 18 34
 */
namespace App;
use Slim\App;

class Router {
    /**
     * @param App $app
     * @return void
     * Register all routes in app instance
     */
    static public function registerRoutes(App &$app) : void {
        $app->get('/', 'RefairController:index')->setName('home');
        $app->get('/csrftoken', 'AuthController:csrftoken')->setName('refair.token');
        $app->get('/jwt', 'AuthController:jwttoken')->setName('refair.jwttoken');
        $app->get('/eval', 'RefairController:evalkeywords')->setName('refair.eval'); // All jobs

        $app->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
        $app->post('/auth/signup', 'AuthController:postSignUp');
        $app->get('/auth/confirm', 'AuthController:confirmEmail')->setName('auth.confirm');
        $app->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
        $app->get('/auth/recover', 'AuthController:getChangePass')->setName('auth.recover');
        $app->get('/auth/recover/{hash}', 'AuthController:getChangePass');
        $app->post('/auth/recover', 'AuthController:postChangePass');
        $app->post('/auth/change', 'AuthController:changePass')->setName('auth.pwdxchng');

        $app->get('/api/job/{id}', 'RefairController:getjob')->setName('refair.job.get'); // Add new referral
        $app->get('/matchprofile', 'RefairController:matchprofile')->setName('refair.matchprofile'); // All jobs
        $app->get('/matchjob/{id}', 'RefairController:matchjob')->setName('refair.matchjob'); // All jobs
        $app->get('/api/auth/signin', 'AuthController:postSignIn');
        $app->post('/api/user/storeprofile', 'RefairController:storeprofile')->setName('refair.user.storeprofile'); // Add new referral
        $app->post('/api/user/getprofile/{id}', 'RefairController:getprofile')->setName('refair.user.getprofile'); // Add new referral
        $app->get('/api/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');
        $app->post('/api/apply', 'JobController:apply')->setName('refair.apply');
        $app->get('/getapply/{user}', 'JobController:getApply')->setName('refair.apply.user');

        $app->post('/project/add', 'ProjectController:add')->setName('refair.add.project');
        $app->post('/project/update/{id}', 'ProjectController:update')->setName('refair.update.project');
        $app->post('/project/delete/{id}', 'ProjectController:deleteProject')->setName('refair.delete.project');
        $app->get('/project/get/all', 'ProjectController:getProjects')->setName('refair.get.projects');
        $app->get('/project/get/all/{company}', 'ProjectController:getCompanyProjects')->setName('refair.get.company.project');
        $app->get('/project/get/{id}', 'ProjectController:getProject')->setName('refair.get.project');

        $app->get('/getjobs', 'JobController:get')->setName('refair.getjobs'); //All jobs
        $app->get('/getjobs/{page}', 'JobController:get')->setName('refair.getjobs'); //All jobs
        $app->post('/getjobs', 'JobController:get')->setName('refair.getjobs'); //All jobs
        $app->post('/job/add', 'JobController:add')->setName('refair.add.job'); // Add new referral
        $app->post('/api/job/new', 'JonController:add')->setName('refair.add.new.job'); // Add new referral
        $app->post('/job/update/{id}', 'JobController:update')->setName('refair.update.job'); // Add new referral
        $app->get('/job/delete/{id}', 'JobController:delete')->setName('refair.delete.job'); // Add new referral

        $app->post('/referral/add', 'ReferralController:add')->setName('refair.add.referral'); // Add new referral
        $app->get('/referral/all', 'ReferralController:getall')->setName('refair.all.referral'); // Get referral
        $app->get('/referral/get/{id}', 'ReferralController:get')->setName('refair.get.referral'); // Get referral
        $app->get('/referral/delete/{id}', 'ReferralController:delete')->setName('refair.delete.referral'); // Get referral
        $app->post('/referral/update/{id}', 'ReferralController:update')->setName('refair.update.referral'); // Get referral
        $app->get('/getreferral/send/{user}', 'ReferralController:getReferralSend')->setName('refair.getreferral.send');
        $app->get('/getreferral/received/{email}', 'ReferralController:getReferralReceived')->setName('refair.getreferral.received');

        $app->get('/user/get', 'UserController:get');
        $app->post('/user/update', 'UserController:update');

        $app->get('/matchjobs/strong/{uid}', 'RefairController:jobsToStrongUid')->setName('refair.match.job.strong');
        $app->get('/matchprofiles/strong/{jid}', 'RefairController:profilesToStrongJid')->setName('refair.match.profile.strong');

        $app->get('/api/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
        $app->post('/api/auth/password/change', 'PasswordController:postChangePassword');
        $app->get('/api/auth/password/recoverlink', 'PasswordController:recoverLink');
        $app->post('/api/auth/password/recover', 'PasswordController:recover');

        $app->get('/company/get/all', 'RefairController:getCompanies')->setName('refair.get.companies');
        $app->get('/company/get/{id}', 'RefairController:getCompany')->setName('refair.get.company');

        $app->post('/upload', 'RefairController:uploadFile');


        $app->group('', function () use ($app) {
            $app->get('/landing', 'AuthController:getSignUpLanding')->setName('auth.landing');
            $app->get('/processoauth', 'RefairController:processoauth')->setName('oauth2.linkedin');
            $app->post('/processoauth', 'RefairController:processoauth');
            $app->get('/profilebuild', 'RefairController:getBuildProfile')->setName('profile.build');
        });

        $app->group('', function () use ($app) {
            $app->get('/profile', 'ProfileController:index')->setName('profile.dashboard');
            $app->post('/profile', 'ProfileController:index');
            $app->get('/account', 'ProfileController:account')->setName('account');
            $app->post('/account', 'ProfileController:account');

            $app->get('/appointments/get', 'AppointmentsController:get')->setName('appointment.get');
            $app->post('/appointments/add', 'AppointmentsController:create')->setName('appointment.create');
            $app->post('/appointments/delete/{id}', 'AppointmentsController:delete')->setName('appointment.delete');
            $app->post('/appointments/update/{id}', 'AppointmentsController:update')->setName('appointment.update');


            $app->get('/getlocations', 'RefairController:getlocs')->setName('refair.getlocations'); // All locations

            $app->post('/company/add', 'RefairController:addCompany')->setName('refair.add.company');
            $app->post('/company/update/{id}', 'RefairController:updateCompany')->setName('refair.update.company');
            $app->post('/company/delete/{id}', 'RefairController:deleteCompany')->setName('refair.delete.company');
        });

        $app->get('/cli', 'ConsoleController:runCommand');
    }
}
<?php
use App\Middleware\GuestMiddleware;
use App\Middleware\AuthMiddleware;
use Chadicus\Slim\OAuth2\Http\RequestBridge;
use Chadicus\Slim\OAuth2\Http\ResponseBridge;
use Aurmil\Slim\CsrfTokenToView;
use Aurmil\Slim\CsrfTokenToHeaders;
use OAuth2\GrantType;
use OAuth2\Storage;
use \SlimSession\Helper as Session;

$app->get('/','RefairController:index')->setName('home');
$app->get('/csrftoken','AuthController:csrftoken')->setName('refair.token');
$app->get('/jwt','AuthController:jwttoken')->setName('refair.jwttoken');
$app->get('/eval','RefairController:evalkeywords')->setName('refair.eval'); // All jobs

$app->get('/auth/signup','AuthController:getSignUp')->setName('auth.signup');
$app->post('/auth/signup','AuthController:postSignUp');
$app->get('/auth/confirm','AuthController:confirmEmail')->setName('auth.confirm');
$app->get('/auth/signin','AuthController:getSignIn')->setName('auth.signin');
$app->get('/auth/recover','AuthController:getChangePass')->setName('auth.recover');
$app->get('/auth/recover/{hash}','AuthController:getChangePass');
$app->post('/auth/recover','AuthController:postChangePass');
$app->post('/auth/change','AuthController:changePass')->setName('auth.pwdxchng');

$app->get('/api/job/{id}','RefairController:getjob')->setName('refair.job.get'); // Add new referral
$app->get('/matchprofile','RefairController:matchprofile')->setName('refair.matchprofile'); // All jobs
$app->get('/matchjob/{id}','RefairController:matchjob')->setName('refair.matchjob'); // All jobs
$app->get('/api/auth/signin','AuthController:postSignIn');
$app->post('/api/user/storeprofile','RefairController:storeprofile')->setName('refair.user.storeprofile'); // Add new referral
$app->post('/api/user/getprofile/{id}','RefairController:getprofile')->setName('refair.user.getprofile'); // Add new referral
$app->get('/api/auth/signout','AuthController:getSignOut')->setName('auth.signout');
$app->post('/api/apply','RefairController:applyjob')->setName('refair.apply');

$app->post('/project/add','RefairController:addProject')->setName('refair.add.project');
$app->post('/project/update/{id}','RefairController:updateProject')->setName('refair.update.project');
$app->post('/project/delete/{id}','RefairController:deleteProject')->setName('refair.delete.project');
$app->get('/project/get/all','RefairController:getProjects')->setName('refair.get.projects');
$app->get('/project/get/all/{company}','RefairController:getCompanyProjects')->setName('refair.get.company.project');
$app->get('/project/get/{id}','RefairController:getProject')->setName('refair.get.project');


$app->post('/job/add','RefairController:addJob')->setName('refair.add.job'); // Add new referral
$app->post('/api/job/new','RefairController:addJob')->setName('refair.add.new.job'); // Add new referral
$app->post('/job/update/{id}','RefairController:updateJob')->setName('refair.update.job'); // Add new referral
$app->get('/job/delete/{id}','RefairController:deleteJob')->setName('refair.delete.job'); // Add new referral

$app->get('/matchjobs/strong/{uid}', 'RefairController:jobsToStrongUid')->setName('refair.match.job.strong');
$app->get('/matchprofiles/strong/{jid}', 'RefairController:profilesToStrongJid')->setName('refair.match.profile.strong');

$app->get('/api/auth/password/change','PasswordController:getChangePassword')->setName('auth.password.change');
$app->post('/api/auth/password/change','PasswordController:postChangePassword');

//TODO: ofc, no token checking.
//Well fucking done.....



$app->group('',function () {
    $this->get('/landing','AuthController:getSignUpLanding')->setName('auth.landing');
    $this->get('/processoauth', 'RefairController:processoauth')->setName('oauth2.linkedin');
    $this->post('/processoauth','RefairController:processoauth');
    $this->get('/profilebuild','RefairController:getBuildProfile')->setName('profile.build');
    
  })->add(new GuestMiddleware($container));

$app->group('',function () {

    $this->get('/profile','ProfileController:index')->setName('profile.dashboard');
    $this->post('/profile','ProfileController:index');
    $this->get('/account','ProfileController:account')->setName('account');
    $this->post('/account','ProfileController:account');

    $this->get('/getlocations','RefairController:getlocs')->setName('refair.getlocations'); // All locations
    $this->get('/getjobs','RefairController:getjobs')->setName('refair.getjobs'); // All jobs

    //Jan to pała
    $this->post('/company/add','RefairController:addCompany')->setName('refair.add.company');
    $this->post('/company/update/{id}','RefairController:updateCompany')->setName('refair.update.company');
    $this->post('/company/delete/{id}','RefairController:deleteCompany')->setName('refair.delete.company');
    $this->get('/company/get/all','RefairController:getCompanies')->setName('refair.get.companies');
    $this->get('/company/get/{id}','RefairController:getCompany')->setName('refair.get.company');

    $this->get('/job/get/all','RefairController:getJobs')->setName('refair.get.jobs'); // Add new referral
    $this->get('/job/get/{id}','RefairController:getJob')->setName('refair.get.job'); // Add new referral

  })->add(new AuthMiddleware($container));


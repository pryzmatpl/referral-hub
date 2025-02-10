<?php
namespace App\Controllers;
use Nette\Mail\Message;
use Knp\Menu\MenuFactory;
use Respect\Validation\Validator as v;
use Knp\Menu\Renderer\ListRenderer;
use League\OAuth2\Client\Provider\LinkedIn as OauthLI;
use League\OAuth2\Client\Provider\Github as OauthGH;
use App\Models\User;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Referral;
use App\Models\Jobdesc;
use App\Models\Jobweight;
use App\Models\Linkedinimport;
use App\Models\Signoff;
use App\Classes\Fitnesscalc;
use App\Classes\Individual;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use App\Classes\Population;
use App\Classes\Algorithm;
use Illuminate\Database\Capsule\Manager as DB;
use SlimSession\Helper as Session;


class AdminController extends Controller{

    public function index($request, $response, $args){
        $session = new Session;
        $menus = $this->buildmenu();
        $view = 'admin-boot.vue';

        return $this->view->render($response,
            $view,
            array('menus'=>$menus));
    }

    public function menu($request, $response, $args){
        $session = new Session;

        //TODO: Test This
        //Main Menu building function
        $factory = new MenuFactory();
        $menu = array(
            ['header'=>'Admin'],
            ['href'=>'/','title'=>'Home','icon'=>'home'],
            ['href'=>'/crud/types','title'=>'Types','icon'=>'view_list'],
            ['href'=>'/crud/posts','title'=>'Posts','icon'=>'view_list']
        );

        return $response->withJson($menu);
    }

    public function selfhosted($request, $response, $args){
        $session = new Session;
        $menus = $this->buildmenu();
        $view = 'admin-boot.vue';

        return $this->view->render($response,
            $view,
            array('menus'=>$menus));
    }


    public function jobs($request, $response, $args){
        $jobs = Jobdesc::all();
        $burl=env("base_url");
        $returnee = [];
        $asss = 1;
        foreach($jobs as $job){
            $returnee[] = array('avatar'=> array( 'url' => 'static/img/avatars/'.$asss.'.jpg' , 'status' => 'success' ),
                'user' => array('name' => $job['jobtitle'], 'new' => $job['updated_at'], 'registered' => $job['created_at']),
                'country' => array('name' => $job['locationid'], 'flag' => 'static/img/flags/USA.png'),
                'usage' => array('value'=>50, 'period' => 'Jun 11, 2015 - Jul 10, 2015'),
                'payment'=> array('name'=>'Mastercard', 'icon'=>'fa fa-cc-mastercad'),
                'activity'=> '10 sec ago'
            );
        }
        return $this->response->withJson($returnee);
    }


    public function users($request, $response, $args){
        $jobs = User::all();
        $burl=env("base_url");
        $returnee = [];
        $asss = 1;
        foreach($jobs as $job){
            $returnee[] = array('avatar'=> array( 'url' => 'static/img/avatars/'.$asss.'.jpg' , 'status' => 'success' ),
                'user' => array('name' => $job['email'], 'new' => $job['updated_at'], 'registered' => $job['created_at']),
                'country' => array('name' => $job['group_id'], 'flag' => 'static/img/flags/USA.png'),
                'usage' => array('value'=>50, 'period' => 'Jun 11, 2015 - Jul 10, 2015'),
                'payment'=> array('name'=>'Mastercard', 'icon'=>'fa fa-cc-mastercad'),
                'activity'=> '10 sec ago'
            );
        }
        return $this->response->withJson($returnee);
    }



    public function referrals($request, $response, $args){
        $jobs = Referral::all();
        $burl=env("base_url");
        $returnee = [];
        $asss = 1;
        foreach($jobs as $job){
            $returnee[] = array('avatar'=> array( 'url' => 'static/img/avatars/'.$asss.'.jpg' , 'status' => 'success' ),
                'user' => array('name' => $job['referred_id'].' - '. $job['referrer_id'], 'new' => $job['referrer_id'], 'registered' => $job['created_at']),
                'country' => array('name' => $job['group_id'], 'flag' => 'static/img/flags/USA.png'),
                'usage' => array('value'=>50, 'period' => 'Jun 11, 2015 - Jul 10, 2015'),
                'payment'=> array('name'=>'Mastercard', 'icon'=>'fa fa-cc-mastercad'),
                'activity'=> '10 sec ago'
            );
        }
        return $this->response->withJson($returnee);
    }


}

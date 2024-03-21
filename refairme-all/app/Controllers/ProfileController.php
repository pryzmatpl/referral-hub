<?php
namespace App\Controllers;

use App\Models\Cart;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Capsule\Manager as DB;
use \SlimSession\Helper as Session;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carlosocarvalho\SimpleInput\Input\Input;

class ProfileController extends Controller
{
    public function index($request,$response, $args)
    {
        $factory = new MenuFactory();
        $menu = $factory->createItem('TexPriced endpoints');
        $menu->setChildrenAttribute('class','nav navbar-nav');
        $menu->addChild('Home', array('uri' => '/', ));
        $menu->addChild('Concept', array('uri' => '/cart'));
        $menu->addChild('Trends forecast', array('uri' => '/crawler'));
        $menu->addChild('Pricing', array('uri' => '/'));
        $menu->addChild('Account', array('uri' => '/account'));
        $menu->addChild('Factories',array('uri' => '/location'));
        $renderer = new ListRenderer(new \Knp\Menu\Matcher\Matcher());
        $menus = $renderer->render($menu);

        $users = $this->auth->user();
        $users = $users['email'];
        $datsss = DB::table('trackedcarts')->where('newowner',json_encode($users))->get();
        // QUIRKIEST MOTHAFUCKING BUG I'VE seen to date
        $reviewCarts = json_encode($datsss);
    
        $shopabout = file_get_contents('https://7297545fa55025db90542de74bb6e236:491ffa620d7cfbf189873bc929958b89@iwaboo2.myshopify.com/admin/shop.json');
        $carts = file_get_contents('https://7297545fa55025db90542de74bb6e236:491ffa620d7cfbf189873bc929958b89@iwaboo2.myshopify.com/admin/draft_orders.json');
        $pipedrive = file_get_contents('https://api.pipedrive.com/v1/pipelines?api_token=aa4c1a6a950c84416e3bf5d5c79442000b0cd1df');
        $pipedeals = file_get_contents("https://api.pipedrive.com/v1/deals?status=all_not_deleted&start=0&api_token=aa4c1a6a950c84416e3bf5d5c79442000b0cd1df");

        $products = Product::all();
        $storedCarts = Cart::all(); //illuminate
	
        return $this->view->render($response,'profile.twig', array('menus'=>$menus,
                                                                   'users'=>$users,
                                                                   'userproducts'=>$products,
                                                                   'shopabout'=>$shopabout,
                                                                   'draftcarts'=>$carts ,
                                                                   'storedcarts' => $storedCarts,
                                                                   'reviewcarts' => $reviewCarts,
                                                                   'pipelines'=>$pipedrive,
                                                                   'pipedeals' => $pipedeals));
    }

    public function account($request,$response, $args)
    {
        $factory = new MenuFactory();
        $menu = $factory->createItem('TexPriced endpoints');
        $menu->setChildrenAttribute('class','nav navbar-nav');
        $menu->addChild('Home', array('uri' => '/', ));
        $menu->addChild('Concept', array('uri' => '/cart'));
        $menu->addChild('Trends forecast', array('uri' => '/crawler'));
        $menu->addChild('Pricing', array('uri' => '/'));
        $menu->addChild('Factories',array('uri' => '/location'));
        $renderer = new ListRenderer(new \Knp\Menu\Matcher\Matcher());
        $menus = $renderer->render($menu);

        $users = $this->auth->user();
        $users = $users['email'];
        $datsss = DB::table('trackedcarts')->where('newowner',json_encode($users))->get();
        // QUIRKIEST MOTHAFUCKING BUG I'VE seen to date
        $reviewCarts = json_encode($datsss);
    
        $products = Product::all();
        $storedCarts = Cart::all(); //illuminate
	
        return $this->view->render($response,'account.twig', array('menus'=>$menus,
                                                                   'users'=>$users,
                                                                   'userproducts'=>$products,
                                                                   'storedcarts' => $storedCarts));
    }
    
}
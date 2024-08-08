<?php
namespace App\Controllers;

use App\Models\Cart;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Capsule\Manager as DB;
use SlimSession\Helper as Session;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carlosocarvalho\SimpleInput\Input\Input;

class ProfileController extends Controller
{
    public function index($request,$response, $args)
    {

    }

    public function account($request,$response, $args)
    {

    }
    
}
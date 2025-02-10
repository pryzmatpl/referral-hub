<?php
namespace App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;
use App\Models\User;
use App\Models\Cart;
use App\Classes\Fitnesscalc;
use App\Classes\Individual;
use App\Classes\Population;
use App\Classes\Algorithm;
use Illuminate\Database\Capsule\Manager as DB;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use SlimSession\Helper as Session;

class CartController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function querybreakdown($request, $response){
        //Function addded as it's expected to be very useful for AJAX.
        //We shall see
        //GET
        $allGetVars = $request->getQueryParams();

        //POST or PUT
        $allPostPutVars = $request->getParsedBody();
        //POST or PUT parameters list
    }


    public function loadcarts($request, $response){
        //GET
        $allGetVars = $request->getQueryParams();
        $allPostPutVars = $request->getParsedBody();
        //POST or PUT
        $queryHash = $allGetVars['hash'];

        $placearr =  json_decode($this->hashplace($queryHash));

        $appname = $this->traverse($placearr,'SHOPIFYNAME');
        $appid = $this->traverse($placearr,'SHOPIFYID');
        $appkey = $this->traverse($placearr,'SHOPIFYKEY');
        $draftOrders = json_decode($this->getdrafts($appname, $appid, $appkey));
        $cartlist = [];
        foreach($draftOrders as $cart){
            $cartlist[] = array('id'=>$cart->id,
                'status'=>$cart->status,
                'name'=>$cart->name,
                'created_at'=>$cart->created_at,
                'total_price'=>$cart->total_price);
        }
        return json_encode($cartlist);
    }

    public function loadcarts_json($request, $response){
        try{
            //GET
            $allGetVars = $request->getQueryParams();
            $allPostPutVars = $request->getParsedBody();
            //POST or PUT

            $queryHash = $allGetVars['hash'];

            $placearr =  json_decode($this->hashplace($queryHash));

            $appname = $this->traverse($placearr,'SHOPIFYNAME');
            $appid = $this->traverse($placearr,'SHOPIFYID');
            $appkey = $this->traverse($placearr,'SHOPIFYKEY');

            $analyze = json_decode( $this->getdrafts($appname, $appid, $appkey) );

            $totalret = array();

            $margin = 0.4;
            foreach($analyze as $cart){
                foreach($cart->line_items as $item){
                    $qnt = $item->quantity;
                    $prc = $item->price;
                    $profit = $qnt*$prc*$margin;

                    $quantityTotal += $qnt;
                    $skus += 1;
                    $projectedProfit += $profit;
                }

                $newcart = array('cart'=>$cart);
                $newcart['noskus'] = $skus;
                $newcart['projectedprofit'] = "$projectedProfit";
                $newcart['retailmargin'] = '0.4';
                $newcart['allquantity'] = $quantityTotal;
                $totalret[]=$newcart;
            }

            $session = new Session;
            $session['currcarts'] = array('carts'=>$totalret); //Representation of all carts

            return json_encode(array('carts'=>$totalret));
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function getthumbs($request, $response, $args){
        $pdid = $args['id'];

        $jsond = json_decode(file_get_contents('https://7297545fa55025db90542de74bb6e236:491ffa620d7cfbf189873bc929958b89@iwaboo2.myshopify.com/admin/products/'.$pdid.'/images.json'));

        return json_encode($jsond);
    }

    public function additemtocart($request, $response, $args){
        try{
            $session = new Session;

            $hash = $args['ophash'];
            $unwrap=array();
            $data = $this->iwadehash($hash,$unwrap);
            $data = explode('~', $data);
            $operands = $this->getOps($data);

            if(isset($session['optcart'])){
                $optcart = $session['optcart'];
                foreach($optcart->line_items as $lineitem){
                    if( !strcmp($lineitem->product_id, $operands['PRODID'])){
                        $lineitem->quantity += 1 ;
                        break;
                    }
                }
                $session['optcart'] = $optcart;
                return json_encode(array('carts'=>$optcart));
            }else{
                $currentCarts = $session['currcarts']['carts'];
                $loadedcart = $session->get('optcart');
                $tabid = 0;
                foreach($currentCarts as $cart){
                    foreach($cart as $cartin){
                        if( (int)$cartin->id == (int)$operands['CARTID']){
                            foreach($cartin->line_items as $elem){
                                if( !strcmp($elem->product_id, $operands['PRODID'])){
                                    $elem->quantity += 1 ;
                                    break;
                                }
                            }
                        }
                    }
                }
                $session['currcarts'] = array('carts'=>$currentCarts);
                return json_encode(array('carts'=>$currentCarts));
            }
        }catch(Exception $e){
            print_r($e);
        }
    }

    function swap3(&$x,&$y) {
        //https://stackoverflow.com/questions/3541730/is-there-a-php-function-for-swapping-the-values-of-two-variables
        $tmp=$x;
        $x=$y;
        $y=$tmp;
    }

    public function setquantity($request, $response, $args){
        try{
            $session = new Session;

            $allPostPutVars = $request->getParsedBody();
            $prodid = $allPostPutVars['prodid'];
            $varid = $allPostPutVars['variantid'];
            $quantity = $allPostPutVars['actquantity'];
            $cartid = $allPostPutVars['cartid'];

            print_r($cartid.$prodid);

            foreach($session['optcart']['cart']['line_items'] as $lineitem){
                if( (!strcmp($lineitem['product_id'], $prodid)) && (!strcmp($lineitem['variant_id'], $varid))){
                    $lineitem['quantity'] = $quantity;
                    break;
                }
            }

            $this->flash->addMessage('Test',$quantity);

            $request->reparseBody();
            return $response->withRedirect(cart.mainrevpost,
                array('id'=>$cartid));
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function delitemtocart($request, $response, $args){
        try{
            $session = new Session;

            $hash = $args['ophash'];
            $unwrap=array();
            $data = $this->iwadehash($hash,$unwrap);
            $data = explode('~', $data);
            $operands = $this->getOps($data);

            if(isset($session['optcart'])){
                $optcart = $session['optcart'];
                foreach($optcart->line_items as $lineitem){
                    if( !strcmp($lineitem->product_id, $operands['PRODID'])){
                        $lineitem->quantity -= 1 ;
                        break;
                    }
                }
                $session['optcart'] = $optcart;
                return json_encode(array('carts'=>$optcart));
            }else{
                $currentCarts = $session['currcarts']['carts'];
                $loadedcart = $session->get('optcart');
                $tabid = 0;
                foreach($currentCarts as $cart){
                    foreach($cart as $cartin){
                        if( (int)$cartin->id == (int)$operands['CARTID']){
                            foreach($cartin->line_items as $elem){
                                if( !strcmp($elem->product_id, $operands['PRODID'])){
                                    $elem->quantity -= 1 ;
                                    break;
                                }
                            }
                            $tabid++;
                        }
                    }
                }
                $session['currcarts'] = array('carts'=>$currentCarts);
                return json_encode(array('carts'=>$currentCarts));
            }
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function sendreview($request, $response){
        return $response."REVIEWPLACEHOLDER";
    }

    public function checkout($request, $response){
        return $response."CHECKOUTPLACEHOLDER";
    }

    public function getdrafts($appname, $appid, $appkey){
        $jsond = json_decode(file_get_contents('https://7297545fa55025db90542de74bb6e236:491ffa620d7cfbf189873bc929958b89@iwaboo2.myshopify.com/admin/draft_orders.json'));

        $carts = array();

        foreach($jsond->draft_orders as $cart){
            $carts[] = $cart;
        }

        return json_encode($carts);
        // $querystr = "https://".$appid.":".$appkey."@".$appname.".myshopify.com/admin/draft_orders.json";
        // return file_get_contents($querystr) or die( "Failed getting your carts, please recheck your appname, id, key and validate your app has enabled the private app");
    }

    public function sessioncart($request, $response,$args){
        try{
            $session = new Session;
            //STRICTLY 4 MY D.E.B.U.G.G.A.Z
            if(isset($session['optcart'])){
                $returnee = $session['optcart'] ;

                $allGetVars = $request->getQueryParams();
                $hash = $args['hash'];

                $unwrap=array();
                $data = $this->iwadehash($hash,$unwrap);
                $data = explode('~', $data);
                $operands = $this->getOps($data);

                $fillable = array('originalowner'=>json_encode($operands['ORIGINALOWNER']),
                    'newowner'=>json_encode($operands['NEWOWNER']),
                    'originid'=>json_encode($operands['ORIGINID']),
                    'jsoncart'=>json_encode($returnee),
                    'status'=>'MIGRATED');

                $newcart = new Cart();
                $newcart->fill($fillable);
                $newcart->save();

                return json_encode("Cart is saved for later. It should pop up in the \"iWABOO Carts\" section of ".$operands['NEWOWNER']." profile.");
            }else{
                $this->flash->addMessage('Error','You did not optimize any cart');
            }
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function loadiwacarts($request, $response, $args){
        try{
            $session = new Session;
            //STRICTLY 4 MY D.E.B.U.G.G.A.Z
            $allGetVars = $request->getQueryParams();
            $hash = $args['hash'];
            $shopifyid = $args['id'];

            $unwrap=array();
            $data = $this->iwadehash($hash,$unwrap);
            $data = explode('~', $data);
            $operands = $this->getOps($data);

            $ownedCarts = Cart::where('originalowner',json_encode($operands['EMAIL']))->get(); //illuminate

            $options = array(); //I truly, truly hate myself for this ;(

            foreach($ownedCarts as $cart){
                $valtojson = json_decode($cart['jsoncart']);
                $valid = ($valtojson->draft_order->id);
                $options[] = "<option class=\"explodecart\" data-cartid=\"".$valid."\" value=\"".$cart['id']."\">".$cart['newowner']." <- ".$cart['originalowner']."</option>";
            }

            $session['iwaboocarts'] = $ownedCarts; // Storing DB carts

            foreach($options as $option){
                echo $option;
            }
        }catch(Exception $E){
            print_r($E);
        }
    }

    public function loadiwacartid($request, $response, $args){
        try{
            $session = new Session;
            //STRICTLY 4 MY D.E.B.U.G.G.A.Z
            $allGetVars = $request->getQueryParams();
            $thisid = $args['id'];

            $owned = Cart::where('id', intval($thisid))->get()->toArray();

            $session = new Session;
            //STRICTLY 4 MY D.E.B.U.G.G.A.Z
            $optcart = json_decode($owned[0]['jsoncart']);
            $optcart->$cartid = $thisid;
            $session['optcart'] = $optcart;

            return json_encode( $optcart ); // json encoded in db
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function index($request, $response, $args)
    {
        try{
            $session = new Session;
            $carts = array();
            $menus = $this->buildmenu();

            $fromindex = true; //We suppose we're here from main window

            //We know we need the category data from session
            $categories = json_decode($this->getcategories(), true);

            $cartid = $args['id'];

            if($request->isPost()){
                $this->flash->addMessage("info", "Posted to edit the cart");
                $storedCarts = $session['optcart'];

                $posts = $request->getParsedBody();
                $quantity=$posts['actquantity'];
                $prodid=$posts['prodid'];
                $varid=$posts['varid'];
                $cartid=$posts['cartid'];

                foreach($storedCarts->cart->line_items as $lineitem){
                    if( (!strcmp($lineitem->product_id, $prodid)) &&
                        (!strcmp($lineitem->variant_id, $varid)) ){
                        $lineitem->quantity = $quantity;
                        break;
                    }
                }

                $percartData = $this->categorizecartsess($storedCarts->cart,
                    $categories);
            }else{
                if(isset($args['id'])){
                    print_r("isntPost1");
                    $storedCarts = Cart::where('id',$args['id'])->get(); //illuminate
                    $cartss = json_decode($storedCarts[0]['jsoncart'], true);
                    $percartData = $this->categorizecart( $cartss['cart'],
                        $categories);
                }else{
                    $latestcart = Cart::orderBy('created_at', 'desc')->first();
                    $cart = json_decode($latestcart['jsoncart'],true);
                    $percartData = $this->categorizecart( $cart['cart'],
                        $categories);

                }
            }

            $stored = $this->view->fetch('bycategory-frommockup.twig', array('categories'=>$percartData,
                'fromindex'=>$fromindex,
                'cartid'=>$cartid));
            //Get user list
            $users = User::all();
            $optsUsers = '';
            foreach($users as $user){
                $emaild = $user['email'];
                $named = $user['name'];
                $optsUsers = $optsUsers."<option value=\"".$emaild."\">".$named."</option>" ;
            }

            //DIRFTY
            //Building one big string for options.
            //I despise myself
            $request->reparseBody();
            return $this->view->render($response,
                'homecart.twig',
                array('menus'=>$menus ,
                    'emailoptions'=>$optsUsers,
                    'carts'=>$stored,
                    'cartid'=>$cartid,
                    'fromindex'=>$fromindex
                ));
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function loadid($request, $response){
        //You can optimize the cart only from JSON!
        try{
            $session = new Session;
            $allGetVars = $request->getQueryParams();
            $qhash = $allGetVars['hash'];
            $placearr = json_decode($this->hashplace($qhash));
            $cid = $this->traverse($placearr,'CARTID');
            $jsonin = file_get_contents("https://7297545fa55025db90542de74bb6e236:491ffa620d7cfbf189873bc929958b89@iwaboo2.myshopify.com/admin/draft_orders/".$cid.".json");
            $jsond = json_decode($jsonin);
            $returnee = $jsond;
            //STRICTLY 4 MY D.E.B.U.G.G.A.Z
            $session['loadedcart'] = $returnee;

            return json_encode($returnee,true);
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function optimizeid($request, $response,$args){
        try{
            $session = new Session;
            $cid = $args['id'];

            if(isset($session['currcarts'])){
                $currentCarts = $session['currcarts']['carts'];
                foreach($currentCarts as $cart){
                    foreach($cart as $cartin){
                        if( (int)$cartin->id == (int)$cid){
                            $loadedcart = $cartin;
                        }
                        $tabid++;
                    }
                }
            }else{
                $loadedcartbe = json_decode($this->loadid($request,$response),true);
                $loadedcart = $loadedcartbe['draft_order'];
            }

            $arrnew = array();
            foreach($loadedcart->line_items as $line_item){
                $arrnew[] = array('variant_id' => $line_item->variant_id,
                    'product_id' => $line_item->product_id,
                    'quantity'  => $line_item->quantity,
                    'price'  => $line_item->price);
            }

            $optimized = $this->optimize($arrnew);
            $id = 0;

            foreach($loadedcart->line_items as $optelem){
                $optelem->quantity = $optimized[$id++]['quantity'];
            }

            $projectedProfit = 0.0;
            $totalPrice = 0.0;

            foreach($loadedcart->line_items as $item){
                $qnt = $item->quantity;
                $prc = $item->price;
                $profit = (double)$qnt*(double)$prc*0.4;

                $quantityTotal += $qnt;
                $totalPrice += (double)$prc;
                $skus += 1;
                $projectedProfit += $profit;
            }

            $newcart = array('cart'=>$loadedcart);
            $newcart['noskus'] = $skus;
            $newcart['projectedprofit'] = "$projectedProfit";
            $newcart['retailmargin'] = '0.4';
            $newcart['allquantity'] = $quantityTotal;
            $newcart['totalprice'] = "$totalPrice";

            $session['optcart'] = $newcart;

            return json_encode($newcart);
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function getCartDiff($request, $response){
        try{
            $session = new Session;
            $optcart = $session['optcart'];
            $loaded = $session['loadedcart'];

            $diffarr = array();
            $id = 0;

            $totaldiff = 0;

            foreach($loaded->line_items as $optimizedvalue){
                $totaldiff += (-$optcart->line_items[$id]->quantity*$optcart->line_items[$id]->price + $optimizedvalue->quantity*$optimizedvalue->quantity);
                $id++;
                $diffarr = $optcart->line_items[$id]->quantity - $optimizedvalue->quantity;
            }

            $loaded->total_price = $totaldiff;

            $id=0;
            foreach($loaded->line_items as $line_item){
                $line_item->quantity = $diffarr[$id];
                $id++;
            }

            return json_encode($loaded,true);
        }catch(Exception $e){
            print_r($e);
        }

    }


    private function processShopifyLogin()
    {
        if (!isset($_GET['code'])) {
            // Setting up scope
            $options = [
                'scope' => [
                    'read_content', 'write_content',
                    'read_themes', 'write_themes',
                    'read_products', 'write_products',
                    'read_customers', 'write_customers',
                    'read_orders', 'write_orders',
                    'read_draft_orders', 'write_draft_orders',
                    'read_script_tags', 'write_script_tags',
                    'read_fulfillments', 'write_fulfillments',
                    'read_shipping', 'write_shipping',
                    'read_analytics',
                ]
            ];
            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl($options);

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.

            header('Location: ' . $authorizationUrl);

            exit;

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

            if (isset($_SESSION['oauth2state'])) {
                unset($_SESSION['oauth2state']);
            }

            exit('Invalid state');

        } else {

            try {
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                $store = $provider->getResourceOwner($accessToken);

                // Access to Store base information
                echo $store->getName();
                echo $store->getEmail();
                echo $store->getDomain();

                // Use this to interact with an API on the users behalf
                echo $accessToken->getToken();

            } catch (IdentityProviderException $e) {
                // Failed to get the access token or user details.
                exit($e->getMessage());

            }
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('cart_create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        return view('cart_store');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($hid = 0)
    {
        //ID must be our hash
        //
        return view('cart_show');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function optimize(&$optcart){
        $time1 = microtime(true);
        $size = count($optcart);

        $myPop = new Population( $size, $optcart, true);

        $generationCount=0;
        // Evolve our population until we reach an optimum solution
        $most_fit_last = 150;
        while ($myPop->getFittest()->getFitness() > fitnesscalc::getMaxFitness())
        {
            $generationCount++;
            $most_fit=$myPop->getFittest()->getFitness();

            $myPop = algorithm::evolvePopulation($myPop); //create a new generation

            if ($most_fit < $most_fit_last) //display only generations when there has been a change
            {
                $most_fit_last=$most_fit;
                $generation_stagnant=0; //reset stagnant generation counter
            }
            else
                $generation_stagnant++; //no improvement increment may want to end early

            if ( $generation_stagnant > algorithm::$max_generation_stagnant)
            {
                //echo "\n-- Ending TOO MANY (".algorithm::$max_generation_stagnant.") stagnant generations unchanged. Ending APPROX solution below \n..)";
                break;
            }

        }  //end of while loop
        //we're done
        $time2 = microtime(true);

        $genes = $myPop->getFittest()->genes;

        return $genes;
    }

    public function uniqProvider(){
        //Iwaboo credentials here
        $shopifyclientid = '7297545fa55025db90542de74bb6e236';
        $shopifysecretid = '491ffa620d7cfbf189873bc929958b89';
        $redirect = 'http://54.193.26.78/home';
        $shop = 'iwaboo2.myshopify.com';

        $provider = new Shopify([
            'clientId'                => $shopifyclientid,    // The client ID assigned to you by the Shopify
            'clientSecret'            => $shopifysecretid,   // The client password assigned to you by the Shopify
            'redirectUri'             => $redirect, // The redirect URI assigned to you
            'shop'                    => $shop, // The Shop name
        ]);

        return $provider;
    }

    public function getProvider($shopifyclientid, $shopifysecretid, $shop){
        //we redirect to our app if we will build public apps, but right now
        //its a blueprint to get providers for private apps
        $redirect = 'http://54.193.26.78/home';

        $provider = new Shopify([
            'clientId'                => $shopifyclientid,    // The client ID assigned to you by the Shopify
            'clientSecret'            => $shopifysecretid,   // The client password assigned to you by the Shopify
            'redirectUri'             => $redirect, // The redirect URI assigned to you
            'shop'                    => $shop, // The Shop name
        ]);

        return $provider;
    }

    private function support_processOperandsClean($hid, $operands){
        $decr=array();
        $changes=array();
        $decrypted_arr = $this->iwadehash($hid,$decr);
        $changed_arr = $this->iwadehash($operands,$changes);

        return $hid;
    }

    private function support_processOperandsHash($hid, $operands){
        $decr=array();
        $changes=array();
        $decrypted_arr = $this->iwadehash($hid,$decr);
        $changed_arr = $this->iwadehash($operands,$changes);

        return $hid;
    }

    public function edit($hid, $operands)
    {
        $decrypted_arr = $this->support_processOperandsClean($hid,$operands);

        return view('cart_edit',['hashorg'=>$decrypted_arr]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $hid = 0)
    {
        return view('cart_update');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($hid = 0)
    {
        return view('cart_destroy');
        //
    }

    //Enable review
    public function review($hid = 0)
    {
        return view('cart_review');
    }

    function getOps($data){
        $operands = array();

        foreach($data as $op){
            $newDat = explode(':',$op);
            $operands[$newDat[0]] = $newDat[1];
        }

        return $operands;
    }

    function unsetValue(array $array, $value, $strict = TRUE)
    {
        //With <3 from Stack overflow https://stackoverflow.com/questions/3573313/php-remove-object-from-array
        if(($key = array_search($value, $array, $strict)) !== FALSE) {
            unset($array[$key]);
        }
        return $array;
    }

}

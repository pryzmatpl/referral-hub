<?php
namespace App\Controllers;

use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Database\Capsule\Manager as DB;
use Requests;
use SlimSession\Helper as Session;

class HomeController extends Controller
{

  public function index($request,$response)
  {
    try{
      $menus = $this->buildmenu();
      $session = new Session;

      $viewedcats = $this->view->fetch('partial_shopifycategories.twig', array('categories'=>$cats));
      $categorybd = $this->view->fetch('categories.twig', array('categories'=>$currentCats));
    
      return $this->view->render($response,'jobs.vue', array('menus'=>$menus,
							     'loadfooter'=>true,
							     'cartdata'=>$latestcart,
							     'cartid'=>$cartid,
							     'originalOwner'=>$originalOwner,
							     'newOwner'=>$newOwner,
							     'shopifyOrigin'=>$shopifyOrigin,
							     'totalorderprice'=>$totalprice,
							     'totalprofit'=>$totalprofit,
							     'currentcats'=>$categorybd,
							     'percartdata'=>$bycartdata,
							     'forceindex'=>true));
    }catch(Exception $e){
      return $e;
    }
  }
  
  public function questionnaire($request,$response)
  {
    $menus = $this->buildmenu();
    return $this->view->render($response,'survey.twig', array('menus'=>$menus));
  }
  
  public function survey($request, $response, $args){
    $sfshit = file_get_contents("https://na50.salesforce.com/flow/Questionnaire_Customers/3016A000000bqkyQAA");
    return $this->view->render($response,'survey.twig', array('menus'=>$menus,'sfshit'=>$sfshit));
  }
	  
	  
  public function addcustomer($request, $response){
    //This one is an endpoint for the crawler, triggered by email detection in website body
        
    $sfcustomeraddendpoint ="https://cloudconversion-api-developer-edition.na17.force.com/services/apexrest/ECS/customers";
    Requests::register_autoloader();
    $headers = array('Accept' => 'application/json');
    //$result = \Requests::get($sfcustomeraddendpoint,$headers)->body; //Here we will be upserting customers
    //Via Cloudconversion 
    // $addcustomerjson =["{\"customers\": 
    //                    [
    // {
    //     \"id\": \"432423432\",
    //     \"companyName\": \"ACME\",
    //     \"companyPhone\":\"888-555-1212\",
    //     \"companyCustomFields\": [
    // {
    //     \"field\": \"Pro_Status__c\",
    //     \"value\": \"Platinum\"
    // },
    // {
    //     \"field\": \"Customer_Value__c\",
    //     \"value\": \"13033\"
    // }
    //     ],
    //     \"email\": \"jcvds@dothesplits.com\",
    //     \"firstName\": \"Jeans\",
    //     \"lastName\": \"Claude Van-Dammes\",
    //     \"phone\":\"415-555-1212 x100\",
    //     \"shippingAddress\" : 
    //     {
    //         \"addressLine1\":\"55 Main St\",
    //         \"addressLine2\":\"Suite 202\",
    //         \"addressLine3\":\"Attn: Mr Van-Dammes\",
    //         \"city\":\"Park City\",
    //         \"state\":\"UT\",
    //         \"postCode\":\"84060\",
    //         \"country\":\"US\"
    //     },
    //     \"billingAddress\" : 
    //     {
    //         \"addressLine1\":\"55 Main St\",
    //         \"addressLine2\":\"Suite 202\",
    //         \"addressLine3\":\"Attn: Mr Van-Dammes\",
    //         \"city\":\"Park City\",
    //         \"state\":\"UT\",
    //         \"postCode\":\"84060\",
    //         \"country\":\"US\"
    //     },                     
    //     \"ownerAlias\": \"mjanke\",
    //     //\"site\": \"FaucetDirect.com\",
    //     //\"createdDate\": \"2014-04-28 17:30:41\",
    //     \"customFields\": [
    // {
    //     \"field\": \"Pro_Status__c\",
    //     \"value\": \"Platinum\"
    // },
    // {
    //     \"field\": \"Customer_Value__c\",
    //     \"value\": \"13033\"
    // }
    //     ]
    // }
    //                    ]
    //                  }
        
  }
}
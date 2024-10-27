<?php
//This file keeps PHP track of the JSON objects
//Flying back and forth
//We use them as scaffolds for the rest of the application
//thus its required from bootstrap/app.php


//Yes, they are global. 
define('SCAFFOLD_LINE_ITEM', 
       ["custom"=> false,
        "fulfillable_quantity"=> 1,
        "fulfillment_service"=> "amazon",
        "grams"=> 500,
        "id"=> 669751112,
        "price"=> "199.99",
        "product_id"=> 7513594,
        "quantity"=> 1,
        "requires_shipping"=> true,
        "sku"=> "IPOD-342-N",
        "title"=> "IPod Nano",
        "variant_id"=> 4264112,
        "variant_title"=> "Pink",
        "vendor"=> "Apple",
        "name"=> "IPod Nano - Pink",
        "gift_card"=> false,
        "properties"=>[
            "name"=> "custom engraving",
            "value"=> "Happy Birthday Mom!"
        ],
        "taxable"=> true,
        "tax_lines"=> [
            "title"=> "title of tax line",
            "rate"=> "10",
            "price"=> "18"
        ],
        "applied_discount"=> [
            "title"=> "title of the discount",
            "description"=> "Description of discount!",
            "value"=> "10",
            "value_type"=> "percentage",
            "amount"=> "19.99"
        ]
       ]
);

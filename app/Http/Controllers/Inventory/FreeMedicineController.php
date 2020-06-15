<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Product;
use App\Account;
use App\FreeMedicine;

class FreeMedicineController extends Controller
{
    
    public function getFreeMedicineDetails($id, $account){

    	$product = array();

        $get_account = Account::where('account_name', $account)->first();
       
        $account_id = $get_account['id'];

        
        $get_product = Product::find($id);

        $product['id'] = $get_product->id;
        $product['product_img'] = $get_product->product_img;
        $product['generic'] = $get_product->generic_name;
        $product['brand'] = $get_product->brand_name;
        $product['strength'] = $get_product->strength;
        $product['weight_volume'] = $get_product->weight_volume;
        $product['product_unit'] = $get_product->product_unit;
        $product['quantity_to_get_free'] = $get_product->quantity_to_get_free;
        $product['free'] = $get_product->free;

        if($account != "General"){

            $prod_id =  $product['id'];

            if(FreeMedicine::where('accountID',  $account_id)
                    ->where('productID', $prod_id)
                    ->exists()){


                    $free = FreeMedicine::where('accountID',  $account_id)
                        ->where('productID', $prod_id)->first();
       
                    $product['quantity_to_get_free'] = $free['quantity_to_get_free'];
                    $product['free'] = $free['free'];

            }

                
         }

        
        return json_encode($product); 


    }


    public function updateFreeMedicine(Request $request){

        $product_id = $request->get('update_product_id');
        $account = $request->get('update_account');

       
        $get_account = Account::where('account_name', $account)->first();
        $account_id = $get_account['id'];
 

        $product = Product::find($product_id);

        if($account == "General"){
            $product->quantity_to_get_free = $request->get('update_qty_to_get_free');
            $product->free = $request->get('update_free');

            $product->save();
        }

        
        else{
            
            if(FreeMedicine::where('accountID',  $account_id)
                               ->where('productID', $product_id)
                               ->exists()){


                    $free = FreeMedicine::where ('accountID',  $account_id)
                        ->where('productID', $product_id)->first();
                    
                    $free->quantity_to_get_free = $request->get('update_qty_to_get_free');
                    $free->free = $request->get('update_free');

            }



            else{

                $free = new FreeMedicine([
                    'accountID' => $account_id,
                    'productID' => $product_id,
                    'quantity_to_get_free' => $request->get('update_qty_to_get_free'),
                    'free' => $request->get('update_free')
                ]);

            }


            $free->save();
        }

       
       
        session(['account'=>$account]);

        return redirect()->back();


    }


    public function getFreeMedicineDetailsPerAccount($account){

        $product = array();

        $get_account = Account::where('account_name', $account)->first();
       
        $account_id = $get_account['id'];
      
        $get_product = Product::all();

        for($i = 0; $i < count($get_product); $i++){

            $product[$i]['id'] = $get_product[$i]['id'];
            $product[$i]['product_img'] = $get_product[$i]['product_img'];
            $product[$i]['generic'] = $get_product[$i]['generic_name'];
            $product[$i]['brand'] = $get_product[$i]['brand_name'];
            $product[$i]['strength'] = $get_product[$i]['strength'];
            $product[$i]['weight_volume'] = $get_product[$i]['weight_volume'];
            $product[$i]['product_unit'] = $get_product[$i]['product_unit'];
            $product[$i]['quantity_to_get_free'] = $get_product[$i]['quantity_to_get_free'];
            $product[$i]['free'] = $get_product[$i]['free'];


            if($account != "General"){

                $prod_id =  $product[$i]['id'];

                if(FreeMedicine::where('accountID',  $account_id)
                               ->where('productID', $prod_id)
                               ->exists()){


                    $free = FreeMedicine::where('accountID',  $account_id)
                        ->where('productID', $prod_id)->first();
       
                    $product[$i]['quantity_to_get_free'] = $free['quantity_to_get_free'];
                    $product[$i]['free'] = $free['free'];

                }

              
                
            }

        }

        return json_encode($product); 

    }


}

<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\OrderTransaction;
use App\DeliverTransaction;
use App\OrderMedicine;
use App\OrderBatchNo;
use App\Product;
use Carbon\Carbon;

class ProductMovementController extends Controller
{
    public function viewProductMovement(){

     //  $productArray = Array();

     //  $now= Carbon::now();

     //  $now1 = $now->isoFormat('Y-MM-DD');

     //  $month = $now->isoFormat('MMMM');

     //  $currentMonth = $now->month;
      

     //  $start_date = new Carbon('first day of' . $month);
     //  $start_date1 =   $start_date->isoFormat('Y-MM-DD');
        
     //  $end_date = new Carbon('last day of' . $month);
     //  $end_date1 =   $end_date->isoFormat('Y-MM-DD');


     //  // fetch the data from OrderTransaction Table that are already delivered/Paid
     //  $OrderTransaction_object = OrderTransaction::where('status', 'Delivered')
     //     ->orwhere('status', 'Paid')
     //     ->get();


     

     //  // fetch the data from DeliverTransaction Table that are inside the date range
     //  $DeliverTransactionArray = Array();
     //  $count_item = 0;

     // for($i = 0; $i < count($OrderTransaction_object); $i++){

     //     $DeliverTransaction_object = DeliverTransaction::all();

     //     for($j = 0; $j < count($DeliverTransaction_object); $j++){

     //        if($OrderTransaction_object[$i]['id'] == $DeliverTransaction_object[$j]['order_transaction_id'] && ($DeliverTransaction_object[$j]['delivery_date'] >= $start_date1 && $DeliverTransaction_object[$j]['delivery_date'] <= $end_date1)){

     //           $DeliverTransactionArray[$count_item] = $DeliverTransaction_object[$j];

     //           $count_item++;
     //        }
            
     //     }
     //  }
 

     //  // return  $DeliverTransactionArray;



     //  // // fetch the data from OrderTransaction Table that are inside the date range
     //  // $OrderTransactionArray = Array();
     //  // $count_item = 0;

     //  // for($i = 0; $i < count($OrderTransaction_object); $i++){
        
     //  //    if($OrderTransaction_object[$i]->order_date >= $start_date1 && $OrderTransaction_object[$i]->order_date <= $end_date1){

     //  //        $OrderTransactionArray[$count_item] = $OrderTransaction_object[$i];

     //  //        $count_item++;

     //  //    }
     //  // }

    

      
     //  // fetch the data from OrderMedicine Table that can be considered as sales
     //  $OrderMedicineArray = Array();
     //  $items = 0;  
     
     //  for($i = 0; $i < count($DeliverTransactionArray); $i++){

     //     $OrderMedicine_object = OrderMedicine::all();

     //     for($j = 0; $j < count($OrderMedicine_object); $j++){

     //        if($DeliverTransactionArray[$i]['order_transaction_id'] == $OrderMedicine_object[$j]['order_transaction_id']){

     //           $OrderMedicineArray[$items] = $OrderMedicine_object[$j];

     //           $items++;
     //        }
            
     //     }
     //  }



     //  // fetch the data from OrderBatchNo Table that can be considered as sales
     //  $OrderBatchNoArray = Array();
     //  $items1 = 0;  
     
     //  for($i = 0; $i < count($OrderMedicineArray); $i++){

     //     $OrderBatchNo_object = OrderBatchNo::all();

     //     for($j = 0; $j < count($OrderBatchNo_object); $j++){

     //        if($OrderMedicineArray[$i]['id'] == $OrderBatchNo_object[$j]['order_medicine_id']){

     //           $OrderBatchNoArray[$items1] = $OrderBatchNo_object[$j];
     //           $OrderBatchNoArray[$items1]['product_id'] = $OrderMedicineArray[$i]['product_id'];

     //           $items1++;
     //        }
            
     //     }
     //  }

      
         
     //  // combine the quantity of same product_id 
     //  $productArray1 = Array();
     //  $items2 = 0;

     //  for($i = 0; $i < count($OrderBatchNoArray); $i++){

     //     $check = 0;

     //     for($j = 0; $j < count($productArray1); $j++){

     //        if($productArray1[$j]['product_id'] == $OrderBatchNoArray[$i]['product_id']){

     //           $productArray1[$j]['quantity'] += $OrderBatchNoArray[$i]['quantity'];

     //           $check = 1;

     //        }

     //     }
            

     //     if($check == 0){
     //        $productArray1[$items2] = $OrderBatchNoArray[$i];

     //        $items2++;
     //     }

     //  }


     //  // // get the product details from the given product_id 
     //  // $productArray2 = Array();

     //  // for($i = 0; $i < count($productArray1); $i++){

     //  //    $product_object = Product::where('id', $productArray1[$i]['product_id'])->first();

     //  //    $productArray2[$i]['generic_name'] = $product_object['generic_name'];
     //  //    $productArray2[$i]['brand_name'] = $product_object['brand_name'];
     //  //    $productArray2[$i]['weight_volume'] = $product_object['weight_volume'];
     //  //    $productArray2[$i]['order_qty'] = $productArray1[$i]['quantity'];

     //  //    $productArray2[$i]['start_date'] = $start_date1;
     //  //    $productArray2[$i]['end_date'] = $end_date1;

     //  // }


     //  // get all the products, change order_qty to zero if no quantity sold from the given date
     //  $productArray3 = Array();


     //  $product_object_all = Product::all();

     //  // $items3 = 0;

     //  for($i = 0; $i < count($product_object_all); $i++){

     //     $check = 0;

     //     // 23
     //     //1234

     //     for($j = 0; $j < count($productArray1); $j++){

     //        if($product_object_all[$i]['id'] == $productArray1[$j]['product_id']){

     //           $productArray3[$i]['order_qty'] = $productArray1[$j]['quantity'];

     //           $check = 1;

     //           break;

     //        }

     //     }
            
     //     // the product has no sales based on the given date
     //     if($check == 0){
            
     //        $productArray3[$i]['order_qty'] = 0;
     //     }


     //     $productArray3[$i]['generic_name'] = $product_object_all[$i]['generic_name'];
     //     $productArray3[$i]['brand_name'] = $product_object_all[$i]['brand_name'];
     //     $productArray3[$i]['weight_volume'] =$product_object_all[$i]['weight_volume'];
         
     //     $productArray3[$i]['start_date'] = $start_date1;
     //     $productArray3[$i]['end_date'] = $end_date1;

     //  }

     

    	return view('inventory.movement');

    }


    public function getProductMovement(){

      $productArray = Array();

      $now = Carbon::now();

      $now1 = $now->isoFormat('Y-MM-DD');

      $month = $now->isoFormat('MMMM');

      $currentMonth = $now->month;
      

      $start_date = new Carbon('first day of' . $month);
      $start_date1 =   $start_date->isoFormat('Y-MM-DD');
        
      $end_date = new Carbon('last day of' . $month);
      $end_date1 =   $end_date->isoFormat('Y-MM-DD');


      // fetch the data from OrderTransaction Table that are already delivered/Paid
      $OrderTransaction_object = OrderTransaction::where('status', 'Delivered')
         ->orwhere('status', 'Paid')
         ->get();


     

      // fetch the data from DeliverTransaction Table that are inside the date range
      $DeliverTransactionArray = Array();
      $count_item = 0;

      for($i = 0; $i < count($OrderTransaction_object); $i++){

         $DeliverTransaction_object = DeliverTransaction::all();

         for($j = 0; $j < count($DeliverTransaction_object); $j++){

            if($OrderTransaction_object[$i]['id'] == $DeliverTransaction_object[$j]['order_transaction_id'] && ($DeliverTransaction_object[$j]['delivery_date'] >= $start_date1 && $DeliverTransaction_object[$j]['delivery_date'] <= $end_date1)){

               $DeliverTransactionArray[$count_item] = $DeliverTransaction_object[$j];

               $count_item++;
            }
            
         }
      }
 

      // return  $DeliverTransactionArray;



      // // fetch the data from OrderTransaction Table that are inside the date range
      // $OrderTransactionArray = Array();
      // $count_item = 0;

      // for($i = 0; $i < count($OrderTransaction_object); $i++){
        
      //    if($OrderTransaction_object[$i]->order_date >= $start_date1 && $OrderTransaction_object[$i]->order_date <= $end_date1){

      //        $OrderTransactionArray[$count_item] = $OrderTransaction_object[$i];

      //        $count_item++;

      //    }
      // }

    

      
      // fetch the data from OrderMedicine Table that can be considered as sales
      $OrderMedicineArray = Array();
      $items = 0;  
     
      for($i = 0; $i < count($DeliverTransactionArray); $i++){

         $OrderMedicine_object = OrderMedicine::all();

         for($j = 0; $j < count($OrderMedicine_object); $j++){

            if($DeliverTransactionArray[$i]['order_transaction_id'] == $OrderMedicine_object[$j]['order_transaction_id']){

               $OrderMedicineArray[$items] = $OrderMedicine_object[$j];

               $items++;
            }
            
         }
      }



      // fetch the data from OrderBatchNo Table that can be considered as sales
      $OrderBatchNoArray = Array();
      $items1 = 0;  
     
      for($i = 0; $i < count($OrderMedicineArray); $i++){

         $OrderBatchNo_object = OrderBatchNo::all();

         for($j = 0; $j < count($OrderBatchNo_object); $j++){

            if($OrderMedicineArray[$i]['id'] == $OrderBatchNo_object[$j]['order_medicine_id']){

               $OrderBatchNoArray[$items1] = $OrderBatchNo_object[$j];
               $OrderBatchNoArray[$items1]['product_id'] = $OrderMedicineArray[$i]['product_id'];

               $items1++;
            }
            
         }
      }

      
         
      // combine the quantity of same product_id 
      $productArray1 = Array();
      $items2 = 0;

      for($i = 0; $i < count($OrderBatchNoArray); $i++){

         $check = 0;

         for($j = 0; $j < count($productArray1); $j++){

            if($productArray1[$j]['product_id'] == $OrderBatchNoArray[$i]['product_id']){

               $productArray1[$j]['quantity'] += $OrderBatchNoArray[$i]['quantity'];

               $check = 1;

            }

         }
            

         if($check == 0){
            $productArray1[$items2] = $OrderBatchNoArray[$i];

            $items2++;
         }

      }


      // // get the product details from the given product_id 
      // $productArray2 = Array();

      // for($i = 0; $i < count($productArray1); $i++){

      //    $product_object = Product::where('id', $productArray1[$i]['product_id'])->first();

      //    $productArray2[$i]['generic_name'] = $product_object['generic_name'];
      //    $productArray2[$i]['brand_name'] = $product_object['brand_name'];
      //    $productArray2[$i]['weight_volume'] = $product_object['weight_volume'];
      //    $productArray2[$i]['order_qty'] = $productArray1[$i]['quantity'];

      //    $productArray2[$i]['start_date'] = $start_date1;
      //    $productArray2[$i]['end_date'] = $end_date1;

      // }


      // get all the products, change order_qty to zero if no quantity sold from the given date
      $productArray3 = Array();


      $product_object_all = Product::all();

      // $items3 = 0;

      for($i = 0; $i < count($product_object_all); $i++){

         $check = 0;

         // 23
         //1234

         for($j = 0; $j < count($productArray1); $j++){

            if($product_object_all[$i]['id'] == $productArray1[$j]['product_id']){

               $productArray3[$i]['order_qty'] = $productArray1[$j]['quantity'];

               $check = 1;

               break;

            }

         }
            
         // the product has no sales based on the given date
         if($check == 0){
            
            $productArray3[$i]['order_qty'] = 0;
         }


         $productArray3[$i]['generic_name'] = $product_object_all[$i]['generic_name'];
         $productArray3[$i]['brand_name'] = $product_object_all[$i]['brand_name'];
         $productArray3[$i]['weight_volume'] =$product_object_all[$i]['weight_volume'];
         
         $productArray3[$i]['start_date'] = $start_date1;
         $productArray3[$i]['end_date'] = $end_date1;

      }
        
     
      return json_encode($productArray3);

    }





    public function productMovementDateRange($startDate, $endDate){

      
      // fetch the data from OrderTransaction Table that are already delivered/Paid
      $OrderTransaction_object = OrderTransaction::where('status', 'Delivered')
         ->orwhere('status', 'Paid')
         ->get();


     

      // fetch the data from DeliverTransaction Table that are inside the date range
      $DeliverTransactionArray = Array();
      $count_item = 0;

      for($i = 0; $i < count($OrderTransaction_object); $i++){

         $DeliverTransaction_object = DeliverTransaction::all();

         for($j = 0; $j < count($DeliverTransaction_object); $j++){

            if($OrderTransaction_object[$i]['id'] == $DeliverTransaction_object[$j]['order_transaction_id'] && ($DeliverTransaction_object[$j]['delivery_date'] >= $startDate && $DeliverTransaction_object[$j]['delivery_date'] <= $endDate)){

               $DeliverTransactionArray[$count_item] = $DeliverTransaction_object[$j];

               $count_item++;
            }
            
         }
      }
 

      // return  $DeliverTransactionArray;



      // // fetch the data from OrderTransaction Table that are inside the date range
      // $OrderTransactionArray = Array();
      // $count_item = 0;

      // for($i = 0; $i < count($OrderTransaction_object); $i++){
        
      //    if($OrderTransaction_object[$i]->order_date >= $start_date1 && $OrderTransaction_object[$i]->order_date <= $end_date1){

      //        $OrderTransactionArray[$count_item] = $OrderTransaction_object[$i];

      //        $count_item++;

      //    }
      // }

    

      
      // fetch the data from OrderMedicine Table that can be considered as sales
      $OrderMedicineArray = Array();
      $items = 0;  
     
      for($i = 0; $i < count($DeliverTransactionArray); $i++){

         $OrderMedicine_object = OrderMedicine::all();

         for($j = 0; $j < count($OrderMedicine_object); $j++){

            if($DeliverTransactionArray[$i]['order_transaction_id'] == $OrderMedicine_object[$j]['order_transaction_id']){

               $OrderMedicineArray[$items] = $OrderMedicine_object[$j];

               $items++;
            }
            
         }
      }



      // fetch the data from OrderBatchNo Table that can be considered as sales
      $OrderBatchNoArray = Array();
      $items1 = 0;  
     
      for($i = 0; $i < count($OrderMedicineArray); $i++){

         $OrderBatchNo_object = OrderBatchNo::all();

         for($j = 0; $j < count($OrderBatchNo_object); $j++){

            if($OrderMedicineArray[$i]['id'] == $OrderBatchNo_object[$j]['order_medicine_id']){

               $OrderBatchNoArray[$items1] = $OrderBatchNo_object[$j];
               $OrderBatchNoArray[$items1]['product_id'] = $OrderMedicineArray[$i]['product_id'];

               $items1++;
            }
            
         }
      }

      
         
      // combine the quantity of same product_id 
      $productArray1 = Array();
      $items2 = 0;

      for($i = 0; $i < count($OrderBatchNoArray); $i++){

         $check = 0;

         for($j = 0; $j < count($productArray1); $j++){

            if($productArray1[$j]['product_id'] == $OrderBatchNoArray[$i]['product_id']){

               $productArray1[$j]['quantity'] += $OrderBatchNoArray[$i]['quantity'];

               $check = 1;

            }

         }
            

         if($check == 0){
            $productArray1[$items2] = $OrderBatchNoArray[$i];

            $items2++;
         }

      }


      // // get the product details from the given product_id 
      // $productArray2 = Array();

      // for($i = 0; $i < count($productArray1); $i++){

      //    $product_object = Product::where('id', $productArray1[$i]['product_id'])->first();

      //    $productArray2[$i]['generic_name'] = $product_object['generic_name'];
      //    $productArray2[$i]['brand_name'] = $product_object['brand_name'];
      //    $productArray2[$i]['weight_volume'] = $product_object['weight_volume'];
      //    $productArray2[$i]['order_qty'] = $productArray1[$i]['quantity'];

      //    $productArray2[$i]['start_date'] = $start_date1;
      //    $productArray2[$i]['end_date'] = $end_date1;

      // }


      // get all the products, change order_qty to zero if no quantity sold from the given date
      $productArray3 = Array();


      $product_object_all = Product::all();

      // $items3 = 0;

      for($i = 0; $i < count($product_object_all); $i++){

         $check = 0;

         // 23
         //1234

         for($j = 0; $j < count($productArray1); $j++){

            if($product_object_all[$i]['id'] == $productArray1[$j]['product_id']){

               $productArray3[$i]['order_qty'] = $productArray1[$j]['quantity'];

               $check = 1;

               break;

            }

         }
            
         // the product has no sales based on the given date
         if($check == 0){
            
            $productArray3[$i]['order_qty'] = 0;
         }


         $productArray3[$i]['generic_name'] = $product_object_all[$i]['generic_name'];
         $productArray3[$i]['brand_name'] = $product_object_all[$i]['brand_name'];
         $productArray3[$i]['weight_volume'] =$product_object_all[$i]['weight_volume'];
         
         // $productArray3[$i]['start_date'] = $start_date1;
         // $productArray3[$i]['end_date'] = $end_date1;

      }
    


      return json_encode($productArray3);

    }
}

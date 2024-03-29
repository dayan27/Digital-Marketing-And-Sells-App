<?php

namespace App\Http\Controllers\Payment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerOrderResource;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\User;
use Chapa\Chapa\Facades\Chapa as Chapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ChapaController extends Controller
{
    /**
     * Initialize Rave payment process
     * @return void
     */
    protected $reference;

    public function __construct(){
        $this->reference = Chapa::generateReference();

    }
    public function initialize(Request $request)
    {

      //  return $request->all();
        
        //This generates a payment reference
        $reference = $this->reference;
        
       $order_id= $this->orderProduct($request,$reference);



       $user=User::find($request->user_id);

        $orderItems=$request->products;
        $totalPrice=0;
        foreach($orderItems as $item){
         $id=$item['id'];
          $item_price=Product::find($id)->price;

          $totalPrice=$totalPrice + (double)$item_price*(double)$item['qty'];
         
        }
 
        // $data = [
            
        //     'amount' => 100,
        //     'email' => 'alemu@gmail.com',
        //     'tx_ref' => $reference,
        //     'currency' => "ETB",
        //     'callback_url' => route('callback',[$reference]),
        //     'first_name' => '$user->first_name',
        //     'last_name' => '$user->last_name',
        //     "customization" => [
        //         "title" => 'Solar Order Payment',
        //         "description" => "Pay To Rensys",
            
        //     ]
        // ];
        
////////=============///
       //Enter the details of the payment
       $data = [
            
        'amount' => $totalPrice,
        'email' => 'alemu@gmail.com',
        'tx_ref' => $reference,
        'currency' => "ETB",
        'callback_url' => route('callback',[$reference]),
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        "customization" => [
        "title" => 'title',
        "description" => "I amma testing this"
        ]
    ];
    
        $payment = Chapa::initializePayment($data);
       // return $payment;

        if ($payment['status'] !== 'success') {
          
            //removing order from the databse
            $this->removeOrder($reference);

            // notify something went wrong
            return response()->json('failed to load payment page',400);
        }

        return($payment['data']['checkout_url']);
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback($reference)
    {
        
        $data = Chapa::verifyTransaction($reference);
      //  dd($data);

        //if payment is successful
        if ($data['status'] ==  'success') {
        
            return redirect(url(env('FRONTEND_USER_URL')).'/order-confirm');

        }

        else{
            //oopsie something ain't right.
            $this->removeOrder($reference);
            return response()->json('failed to verify payment',400);
        }


    }


    private function removeOrder($reference){
        $order=Order::where('transaction_id',$reference)->first();
        $order_addr= OrderAddress::find($order->order_address_id);
        if($order_addr->orders()->count() == 1){
         $order->order_address_id=null;
         $order->save();
         $order_addr->delete();
        }

        $order->order_items()->delete();
        $order->delete();

    }
    private function orderProduct(Request $request,$reference){
        
     try {
      DB::beginTransaction();

     $order=new Order();
     $order->pin=rand(1000,9999);
     $order->pickup_date=date('Y-m-d',strtotime($request->pickup_date));
     $order->user_id=$request->user_id;
     $order->order_status_id=OrderStatus::where('status_name','paid')->first()->id;
     $order->payment_type_id=$request->payment_method;
     $order->shop_id=$request->shop_id;
     $order->transaction_id=$reference;
      //   ret Str::random(10)
     
     $orderItems=$request->products;
     $totalPrice=0;
     foreach($orderItems as $item){
      $id=$item['id'];
       $item_price=Product::find($id)->price;
      //$item_price=$item->prod->price;
     $totalPrice=$totalPrice + (double)$item_price*(double)$item['qty'];
      
     }
     $order->total_price=$totalPrice;

     if ($request->is_new) {
  
      $newaddress= OrderAddress::create([
          'user_region'=> $request->user_region,
          'user_zone'=> $request->user_zone,
          'user_woreda'=> $request->user_woreda,
          'phone_number'=> $request->phone_number,
      ]);
      $order->order_address_id=$newaddress->id;

      }else {
          $order->order_address_id=$request->address_id;

       }
     $order->save();

     foreach($orderItems as $item){

              $orderItem=new OrderItem();
              $orderItem->quantity=$item['qty'];
              $orderItem->order_id=$order->id;
              $orderItem->product_id=$item['id'];
              $orderItem->unit_price=Product::find($item['id'])->price;
              $orderItem->save();

       }
        
      // return $order->id;
       DB::commit();
    } catch (\Throwable $th) {
     return $th;
     DB::rollBack();
    }         

  }
}
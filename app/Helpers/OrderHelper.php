<?php

namespace App\Helpers;

use App\Models\Order\OrderStatusLog;
use App\Models\User\User;
use App\Notifications\Order\OrderPlacedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OrderHelper {

    public static function getAvailableStatus(){
        return [
            'pending', 'waiting_for_payment', 'waiting_for_price', 'payment_approved', 'payment_rejected', 'payment_refunded', 'canceled', 'completed',
            'ready_to_fetch', 'finding_fetcher', 'fetching_order_items', 'ready_for_pickup',
            'ready_for_delivery','finding_delivery','delivery_on_place','collected_by_delivery',
            'transit_to_pickup','transit_to_destination','near_destination','delivered'
        ];
    }

    public static function setStatusPending($order, $autosave = false){
        return self::setStatus($order, 'pending', $autosave);
    }

    public static function setStatusWaitingForPayment($order, $autosave = false){
        return self::setStatus($order, 'waiting_for_payment', $autosave);
    }

    public static function setStatusWaitingForPrice($order, $autosave = false){
        return self::setStatus($order, 'waiting_for_price', $autosave);
    }

    public static function setStatusPaymentApproved($order, $autosave = false){
        return self::setStatus($order, 'payment_approved', $autosave);
    }

    public static function setStatusPaymentRejected($order, $autosave = false){
        return self::setStatus($order, 'payment_rejected', $autosave);
    }

    public static function setStatusPaymentRefunded($order, $autosave = false){
        return self::setStatus($order, 'payment_refunded', $autosave);
    }

    public static function setStatusCanceled($order, $autosave = false){
        return self::setStatus($order, 'canceled', $autosave);
    }

    public static function setStatusCompleted($order, $autosave = false){
        return self::setStatus($order, 'completed', $autosave);
    }

    public static function setStatusReadyForFetcher($order, $autosave = false){
        return self::setStatus($order, 'ready_to_fetch', $autosave);
    }

    public static function setStatusFindingFetcher($order, $autosave = false){
        return self::setStatus($order, 'finding_fetcher', $autosave);
    }

    public static function setStatusFetchingOrderItems($order, $autosave = false){
        return self::setStatus($order, 'fetching_order_items', $autosave);
    }

    public static function setStatusReadyForPickup($order, $autosave = false){
        return self::setStatus($order, 'ready_for_pickup', $autosave);
    }

    public static function setStatusReadyForDelivery($order, $autosave = false){
        return self::setStatus($order, 'ready_for_delivery', $autosave);
    }

    public static function setStatusFindingDelivery($order, $autosave = false){
        return self::setStatus($order, 'finding_delivery', $autosave);
    }

    public static function setStatusDeliveryOnPlace($order, $autosave = false){
        return self::setStatus($order, 'delivery_on_place', $autosave);
    }

    public static function setStatusCollectedByDelivery($order, $autosave = false){
        return self::setStatus($order, 'collected_by_delivery', $autosave);
    }

    public static function setStatusTransitToPickup($order, $autosave = false){
        return self::setStatus($order, 'transit_to_pickup', $autosave);
    }

    public static function setStatusTransitToDesstination($order, $autosave = false){
        return self::setStatus($order, 'transit_to_destination', $autosave);
    }

    public static function setStatusNearDestination($order, $autosave = false){
        return self::setStatus($order, 'near_destination', $autosave);
    }

    public static function setStatusDelivered($order, $autosave = false){
        return self::setStatus($order, 'delivered', $autosave);
    }

    public static function setStatus($order, $status, $autosave = false){
        $available_status = self::getAvailableStatus();
        if(in_array($status, $available_status)){
            $order->status = $status;
            if($autosave){
                $order->save();
            }
            self::createLog($order, $status);
            self::statusNotification($order);
            return $order;
        }
        throw new \Exception('Invalid Order Status');
    }

    public static function createLog($order, $status, $message = null){
        $orderStatusLog = new OrderStatusLog();
        $orderStatusLog->order_id = $order->id;
        $orderStatusLog->status = $status;
        $orderStatusLog->message = $message;
        $orderStatusLog->save();
    }

    public static function valdateStatus($order, $status){
        if($status === 'waiting_for_payment'){
            if($order->transaction_total <= 0){
                throw new \Exception('Transaction price should be more than 0');
            }
        }
        return true;
    }


    public static function statusNotification($order){

        if($order){
            $merchant = $order->merchant;
            $items = [];
            foreach ($order->items as $item) {
                $items[] = [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price > 0 ? $item->price : '-',
                ];
            }


            $hasCustomItems = false;
            if($order->custom_product_request && is_array($order->custom_product_request) && count($order->custom_product_request) > 0){
                $hasCustomItems = true;
                foreach ($order->custom_product_request as $customItem){
                    $items[] = [
                        'name' => $customItem,
                        'quantity' => '',
                        'price' => ($order->status !== 'waiting_for_payment') ? 'Needs confirmation' : '',
                    ];
                }
            }


            if(count($order->items) > 0){
                $items[] = [
                    'name' => 'Total',
                    'quantity' => '',
                    'price' => (($order->status === 'waiting_for_payment' || $order->transaction_status === 'approved' ) || ($order->transaction_total > 0 && !$hasCustomItems)) ? $order->transaction_total :  'Needs confirmation',
                ];
            }

            $details = [
                'greeting' => 'Hi ' . $order->user->name,
                'thanks' => 'Thank you for using ' . env('APP_NAME'),
                'order_id' => $order->id,
                'transaction_id' => $order->transaction_id,
                'tableHeader' => [
                    'Product',
                    'Qty',
                    'Price'
                ],
                'tableBody' => $items
            ];


            $details_merchant = [
                'greeting' => 'Hi ' . strtoupper($merchant->name),
                'thanks' => 'You have a new Order ' . env('APP_NAME'),
                'order_id' => $order->id,
                'transaction_id' => $order->transaction_id,
                'tableHeader' => [
                    'Product',
                    'Qty',
                    'Price'
                ],
                'tableBody' => $items
            ];


            $details_admin = [
                'greeting' => 'Hi ',
                'thanks' => 'You have a new Order ' . env('APP_NAME'),
                'order_id' => $order->id,
                'transaction_id' => $order->transaction_id,
                'tableHeader' => [
                    'Product',
                    'Qty',
                    'Price'
                ],
                'tableBody' => $items
            ];


            $notify = false;
            $notify_admin = false;
            $notify_merchant = false;

            //$recipientPhone = '+5493515929601';
            $recipientPhone = '+12462625075';

            switch ($order->status){
                case 'waiting_for_price':
                    $details['subject'] = 'Order Placed';
                    $details['body'] = 'Your order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was placed successfully, you will receive a payment email as soon as we process your order';
                    $notify = true;

                    $details_admin['subject'] = 'Order Placed';
                    $details_admin['body'] = 'The order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' needs price confirmation';
                    $details_admin['actionText'] = 'View order';
                    $details_admin['actionURL'] =  route('backend.order.show', $order->id);

                    $notify_admin = true;

                    SMSHelper::sendMessage('Order Ready to set Price ' . route('backend.order.show', $order->id), $recipientPhone);
                    break;
                case 'waiting_for_payment':
                    $details['subject'] = 'Payment request';
                    $details['body'] = 'Your order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' is ready to be processed, you can pay using the "Pay now" button';
                    $details['actionText'] = 'Pay now';
                    $details['actionURL'] =  route('external_payment.view', $order->transaction_id);
                    $notify = true;
                    break;
                case 'payment_approved':
                    $details['subject'] = 'Payment approved';
                    $details['body'] = 'The payment for order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was approved successfully';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;

                    $details_admin['subject'] = 'Payment approved';

                    $adminOrderBody = 'The order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' made by ' . $order->user->name  . ' was approved successfully and is ready to be processed';
                    $adminOrderBody .= '<br/><ul style="padding-left:15px;font-size: 13px">';
                    $adminOrderBody .= "<li style='padding-bottom: 4px'><strong>Customer:</strong> {$order->order_name}  {$order->order_last_name}</li>";
                    $adminOrderBody .= "<li style='padding-bottom: 4px'><strong>Email:</strong> <a href='mailto:{$order->order_email}'>{$order->order_email}</a></li>";
                    $adminOrderBody .= $order->order_home_phone ? "<li style='padding-bottom: 4px'><strong>Home Phone: </strong> <a href='tel:{$order->order_home_phone}'>{$order->order_home_phone}</a></li>" : '';
                    $adminOrderBody .= $order->order_mobile_phone ? "<li style='padding-bottom: 4px'><strong>Mobile Phone: </strong> <a href='tel:{$order->order_mobile_phone}'>{$order->order_mobile_phone}</a></li>" : '';
                    $adminOrderBody .= "<li style='padding-bottom: 4px'><strong>Delivery Address: </strong>{$order->delivery_address}, {$order->delivery_parish} {$order->delivery_country}</li>";
                    $adminOrderBody .= $order->delivery_phone ? "<li style='padding-bottom: 4px'><strong>Delivery Phone: </strong>{$order->delivery_phone}</li>" : '';
                    $adminOrderBody .= $order->delivery_instructions ? "<li style='padding-bottom: 4px'><strong>Delivery Instruction: </strong>{$order->delivery_instructions}</li>" : '';
                    $adminOrderBody .= $order->delivery_date ? "<li style='padding-bottom: 4px'><strong>Delivery Date: </strong>{$order->delivery_date} </li>" : '';
                    $adminOrderBody .= $order->delivery_timeframe ? "<li style='padding-bottom: 4px'><strong>Delivery Time: </strong>{$order->delivery_timeframe}</li>" : '';
                    $adminOrderBody .= $order->order_comment ? "<li style='padding-top: 20px;padding-bottom: 4px'><strong>Order Comment: </strong>{$order->order_comment}</li>" : '';
                    $adminOrderBody .= $order->transaction_total ? "<li style='padding-top: 20px;padding-bottom: 4px'><strong>Transaction Total: </strong>{$order->transaction_total}</li>" : '';
                    $adminOrderBody .= $order->transaction_shipping ? "<li style='padding-bottom: 4px'><strong>Transaction Shipping: </strong>{$order->transaction_shipping}</li>" : '';
                    $adminOrderBody .= $order->transaction_handling_cost ? "<li style='padding-bottom: 4px'><strong>Transaction Fee: </strong>{$order->transaction_handling_cost}</li>" : '';
                    $adminOrderBody .= '</ul>';
                    $details_admin['body'] = $adminOrderBody;

                    $details_admin['actionText'] = 'View order';
                    $details_admin['actionURL'] =  route('backend.order.show', $order->id);


                    $details_merchant['subject'] = 'You have a new order ready to be processed';
                    $merchantOrderBody = 'There is a new Order made by ' . $order->user->name  . ' that is approved and is ready to be processed';
                    $merchantOrderBody .= '<br/><ul style="padding-left:15px;font-size: 13px">';
                    $merchantOrderBody .= "<li style='padding-bottom: 4px'><strong>Customer:</strong> {$order->order_name}  {$order->order_last_name}</li>";
                    $merchantOrderBody .= "<li style='padding-bottom: 4px'><strong>Email:</strong> <a href='mailto:{$order->order_email}'>{$order->order_email}</a></li>";
                    $merchantOrderBody .= $order->order_home_phone ? "<li style='padding-bottom: 4px'><strong>Home Phone: </strong> <a href='tel:{$order->order_home_phone}'>{$order->order_home_phone}</a></li>" : '';
                    $merchantOrderBody .= $order->order_mobile_phone ? "<li style='padding-bottom: 4px'><strong>Mobile Phone: </strong> <a href='tel:{$order->order_mobile_phone}'>{$order->order_mobile_phone}</a></li>" : '';
                    $merchantOrderBody .= "<li style='padding-bottom: 4px'><strong>Delivery Address: </strong>{$order->delivery_address}, {$order->delivery_parish} {$order->delivery_country}</li>";
                    $merchantOrderBody .= $order->delivery_phone ? "<li style='padding-bottom: 4px'><strong>Delivery Phone: </strong>{$order->delivery_phone}</li>" : '';
                    $merchantOrderBody .= $order->delivery_instructions ? "<li style='padding-bottom: 4px'><strong>Delivery Instruction: </strong>{$order->delivery_instructions}</li>" : '';
                    $merchantOrderBody .= $order->delivery_date ? "<li style='padding-bottom: 4px'><strong>Delivery Date: </strong>{$order->delivery_date} </li>" : '';
                    $merchantOrderBody .= $order->delivery_timeframe ? "<li style='padding-bottom: 4px'><strong>Delivery Time: </strong>{$order->delivery_timeframe}</li>" : '';
                    $merchantOrderBody .= $order->order_comment ? "<li style='padding-top: 20px;padding-bottom: 4px'><strong>Order Comment: </strong>{$order->order_comment}</li>" : '';
                    $merchantOrderBody .= $order->transaction_total ? "<li style='padding-top: 20px;padding-bottom: 4px'><strong>Transaction Total: </strong>{$order->transaction_total}</li>" : '';
                    $merchantOrderBody .= $order->transaction_shipping ? "<li style='padding-bottom: 4px'><strong>Transaction Shipping: </strong>{$order->transaction_shipping}</li>" : '';
                    $merchantOrderBody .= $order->transaction_handling_cost ? "<li style='padding-bottom: 4px'><strong>Transaction Fee: </strong>{$order->transaction_handling_cost}</li>" : '';
                    $merchantOrderBody .= '</ul>';
                    $details_merchant['body'] = $merchantOrderBody;

                    $notify_merchant = true;
                    $notify_admin = true;

                    SMSHelper::sendMessage('Order Ready to Fetch ' . route('backend.order.show', $order->id), $recipientPhone);

                    if($order->merchant && $order->merchant->notification_email){
                        $merchantMailDetails = $details;
                        $merchantMailDetails['greeting'] = 'Hi ' . $order->merchant->name;
                        $merchantMailDetails['subject'] = 'Order ready to process';
                        $merchantMailDetails['body'] = 'Please wait for Wifetch admin to reach out to you';
                        unset($merchantMailDetails['actionText']);
                        unset($merchantMailDetails['actionURL']);
                        Notification::route('mail', $order->merchant->notification_email)->notify(new OrderPlacedNotification($merchantMailDetails));
                    }


                    break;
                case 'payment_rejected':
                    $details['subject'] = 'Payment rejected';
                    $details['body'] = 'The payment for order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was rejected';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
                case 'payment_refunded':
                    $details['subject'] = 'Payment refunded';
                    $details['body'] = 'The payment for order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was refunded';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
                case 'canceled':
                    $details['subject'] = 'Order Canceled';
                    $details['body'] = 'The order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was canceled';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
                case 'collected_by_delivery':
                    $details['subject'] = 'Order Collected';
                    $details['body'] = 'The order was collected' . ($merchant ? ' from ' . strtoupper($merchant->name) : '');
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
                case 'transit_to_destination':
                    $details['subject'] = 'Order in transit to destination';
                    $details['body'] = 'The order is in transit to destination';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
                case 'near_destination':
                    $details['subject'] = 'Order near destination';
                    $details['body'] = 'The order is near destination';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
                case 'delivered':



//                    $details['subject'] = 'Order delivered';
//                    $details['body'] = 'The order was delivered, thank you for using ' . env('APP_NAME');

                    $details['subject'] = 'Your order was delivered!';
                    $details['body'] = 'Your order' . ($order ? ' from ' . strtoupper($order->merchant->name) : '') . ' has been delivered';
                    $details['actionText'] = 'Rate our service';
                    $details['actionURL'] =  route('service.rate', $order->transaction_id);

                    $notify = true;
                    break;
            }



//    public static function getAvailableStatus(){
//        return [
//            'pending', 'waiting_for_payment', 'waiting_for_price', 'payment_approved', 'payment_rejected', 'payment_refunded', 'canceled', 'completed',
//            'ready_to_fetch', 'finding_fetcher', 'fetching_order_items', 'ready_for_pickup',
//            'ready_for_delivery','finding_delivery','delivery_on_place','collected_by_delivery',
//            'transit_to_pickup','transit_to_destination','near_destination','delivered'
//        ];
//    }

            if($notify){
                if($order->user){
                    $order->user->notify(new OrderPlacedNotification($details));
                }

            }


            if($notify_merchant){
//                if($order->user && $order->user->email === 'patosmack@gmail.com') {
//
//                }else{
                    if ($merchant->user) {
                        $merchant->user->notify(new OrderPlacedNotification($details_merchant));
                    }
                //}
//                $admin_0 = User::where('email', '=', 'patosmack@gmail.com')->first();
//                if($admin_0){
//                    //
//                    $admin_0->notify(new OrderPlacedNotification($details_merchant));
//                }
            }

            if($notify_admin){
                if($order->user && $order->user->email === 'patosmack@gmail.com'){
                    $admin_0 = User::where('email', '=', 'patosmack@gmail.com')->first();
                    if($admin_0){
                        $admin_0->notify(new OrderPlacedNotification($details_admin));
                    }
                }else{
                    $admin_1 = User::where('email', '=', 'lily@wifetch.com')->first();
                    if($admin_1){
                        $admin_1->notify(new OrderPlacedNotification($details_admin));
                    }
                    $admin_2 = User::where('email', '=', 'lily@caribound.com')->first();
                    if($admin_2){
                        $admin_2->notify(new OrderPlacedNotification($details_admin));
                    }
                    $admin_3 = User::where('email', '=', 'sophie@wifetch.com')->first();
                    if($admin_3){
                        $admin_3->notify(new OrderPlacedNotification($details_admin));
                    }
                    $admin_4 = User::where('email', '=', 'ssmith@wifetch.com')->first();
                    if($admin_4){
                        $admin_4->notify(new OrderPlacedNotification($details_admin));
                    }
                }

            }
        }



//
//        $details = [
//            'subject' => '',
//            'greeting' => 'Hi ' . $user->name,
//            'body' => 'Your order was placed successfully',
//            'thanks' => 'Thank you for using ' . env('APP_NAME'),
//            'actionText' => 'View your orders',
//            'actionURL' => route('account.orders'),
//            'order_id' => $order->id,
//        ];
//        $user->notify(new OrderPlacedNotification($details));

    }


    public static function statusOrderTransactionNotification($orderTransaction){

        if($orderTransaction && $orderTransaction->order){

            $order = $orderTransaction->order;
            $merchant = $order->merchant;

            $items[] = [
                'name' => $orderTransaction->transaction_description,
                'price' => '$' . round($orderTransaction->transaction_total, 2),
            ];

            $details = [
                'greeting' => 'Hi ' . $order->user->name,
                'thanks' => 'Thank you for using ' . env('APP_NAME'),
                'order_id' => $order->id,
                'transaction_id' => $orderTransaction->transaction_id,
                'tableHeader' => [
                    'Description',
                    'Requested Amount'
                ],
                'tableBody' => $items
            ];

            $details_admin = [
                'greeting' => 'Hi ',
                'thanks' => 'Payment Requested notification ' . env('APP_NAME'),
                'order_id' => $order->id,
                'transaction_id' => $orderTransaction->transaction_id,
                'tableHeader' => [
                    'Description',
                    'Requested Amount'
                ],
                'tableBody' => $items
            ];

            $notify = false;
            $notify_admin = false;

            //$recipientPhone = '+5493515929601';
            $recipientPhone = '+12462625075';

            //'pending','pending_transaction_email','approved','rejected','refunded','correction_requested','partially_refunded','canceled'

            switch ($orderTransaction->transaction_status){
                case 'pending':
                    $details['subject'] = 'Payment request';
                    $details['body'] = 'Your order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' requires an extra payment';
                    $details['actionText'] = 'Pay now';
                    $details['actionURL'] =  route('extra_payment.view', $orderTransaction->transaction_id);
                    $notify = true;
                    $notify_admin = false;
                    break;
                case 'approved':
                    $details['subject'] = 'Requested Payment Approved';

                    $detailBody = 'The requested payment for order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was approved successfully';
                    $detailBody .= '<br/>';
                    $detailBody .= '<br/><strong>Requested Payment Description</strong>';
                    $detailBody .= '<br/><p>'.$orderTransaction->transaction_description.'</p>';
                    $details['body'] = $detailBody;
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;

                    $details_admin['subject'] = 'Requested Payment approved';

                    $adminOrderBody = 'The payment request for the order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' made by ' . $order->user->name  . ' was approved successfully';
                    $adminOrderBody .= '<br/>';
                    $adminOrderBody .= '<br/><strong>Requested Amount</strong>';
                    $adminOrderBody .= '<br/><p>'.$orderTransaction->transaction_total.'</p>';
                    $adminOrderBody .= '<br/><strong>Requested Payment Description</strong>';
                    $adminOrderBody .= '<br/><p>'.$orderTransaction->transaction_description.'</p>';
                    $adminOrderBody .= '<br/>';
                    $adminOrderBody .= '<strong>Order Information</strong>';
                    $adminOrderBody .= '<br/><ul style="padding-left:15px;font-size: 13px">';
                    $adminOrderBody .= "<li style='padding-bottom: 4px'><strong>Customer:</strong> {$order->order_name}  {$order->order_last_name}</li>";
                    $adminOrderBody .= "<li style='padding-bottom: 4px'><strong>Email:</strong> <a href='mailto:{$order->order_email}'>{$order->order_email}</a></li>";
                    $adminOrderBody .= $order->order_home_phone ? "<li style='padding-bottom: 4px'><strong>Home Phone: </strong> <a href='tel:{$order->order_home_phone}'>{$order->order_home_phone}</a></li>" : '';
                    $adminOrderBody .= $order->order_mobile_phone ? "<li style='padding-bottom: 4px'><strong>Mobile Phone: </strong> <a href='tel:{$order->order_mobile_phone}'>{$order->order_mobile_phone}</a></li>" : '';
                    $adminOrderBody .= "<li style='padding-bottom: 4px'><strong>Delivery Address: </strong>{$order->delivery_address}, {$order->delivery_parish} {$order->delivery_country}</li>";
                    $adminOrderBody .= $order->delivery_phone ? "<li style='padding-bottom: 4px'><strong>Delivery Phone: </strong>{$order->delivery_phone}</li>" : '';
                    $adminOrderBody .= $order->delivery_instructions ? "<li style='padding-bottom: 4px'><strong>Delivery Instruction: </strong>{$order->delivery_instructions}</li>" : '';
                    $adminOrderBody .= $order->delivery_date ? "<li style='padding-bottom: 4px'><strong>Delivery Date: </strong>{$order->delivery_date} </li>" : '';
                    $adminOrderBody .= $order->delivery_timeframe ? "<li style='padding-bottom: 4px'><strong>Delivery Time: </strong>{$order->delivery_timeframe}</li>" : '';
                    $adminOrderBody .= $order->order_comment ? "<li style='padding-top: 20px;padding-bottom: 4px'><strong>Order Comment: </strong>{$order->order_comment}</li>" : '';
                    $adminOrderBody .= $order->transaction_total ? "<li style='padding-top: 20px;padding-bottom: 4px'><strong>Original Transaction Total: </strong>{$order->transaction_total}</li>" : '';
                    $adminOrderBody .= $order->transaction_shipping ? "<li style='padding-bottom: 4px'><strong>Transaction Shipping: </strong>{$order->transaction_shipping}</li>" : '';
                    $adminOrderBody .= $order->transaction_handling_cost ? "<li style='padding-bottom: 4px'><strong>Transaction Fee: </strong>{$order->transaction_handling_cost}</li>" : '';
                    $adminOrderBody .= '</ul>';
                    $details_admin['body'] = $adminOrderBody;

                    $details_admin['actionText'] = 'View order';
                    $details_admin['actionURL'] =  route('backend.order.show', $order->id);

                    $notify_admin = true;

                    SMSHelper::sendMessage('Requested Payment Approved ' . route('backend.order.show', $order->id), $recipientPhone);

//                    if($order->merchant && $order->merchant->notification_email){
//                        $merchantMailDetails = $details;
//                        $merchantMailDetails['greeting'] = 'Hi ' . $order->merchant->name;
//                        $merchantMailDetails['subject'] = 'Order ready to process';
//                        $merchantMailDetails['body'] = 'Please wait for Wifetch admin to reach out to you';
//                        unset($merchantMailDetails['actionText']);
//                        unset($merchantMailDetails['actionURL']);
//                        Notification::route('mail', $order->merchant->notification_email)->notify(new OrderPlacedNotification($merchantMailDetails));
//                    }


                    break;
                case 'rejected':
                    $details['subject'] = 'Payment rejected';
                    $details['body'] = 'The requested payment for order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was rejected';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
                case 'refunded':
                    $details['subject'] = 'Payment refunded';
                    $details['body'] = 'The requested payment for order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was refunded';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
                case 'canceled':
                    $details['subject'] = 'Order Canceled';
                    $details['body'] = 'The requested payment for order' . ($merchant ? ' from ' . strtoupper($merchant->name) : '') . ' was canceled';
                    $details['actionText'] = 'View your orders';
                    $details['actionURL'] =  route('account.orders');
                    $notify = true;
                    break;
            }



//    public static function getAvailableStatus(){
//        return [
//            'pending', 'waiting_for_payment', 'waiting_for_price', 'payment_approved', 'payment_rejected', 'payment_refunded', 'canceled', 'completed',
//            'ready_to_fetch', 'finding_fetcher', 'fetching_order_items', 'ready_for_pickup',
//            'ready_for_delivery','finding_delivery','delivery_on_place','collected_by_delivery',
//            'transit_to_pickup','transit_to_destination','near_destination','delivered'
//        ];
//    }

            if($notify){
                if($order->user){
                    $order->user->notify(new OrderPlacedNotification($details));
                }

            }


            if($notify_admin){
                if($order->user && $order->user->email === 'patosmack@gmail.com'){
                    $admin_0 = User::where('email', '=', 'patosmack@gmail.com')->first();
                    if($admin_0){
                        $admin_0->notify(new OrderPlacedNotification($details_admin));
                    }
                }else{
                    $admin_1 = User::where('email', '=', 'lily@wifetch.com')->first();
                    if($admin_1){
                        $admin_1->notify(new OrderPlacedNotification($details_admin));
                    }
                    $admin_2 = User::where('email', '=', 'lily@caribound.com')->first();
                    if($admin_2){
                        $admin_2->notify(new OrderPlacedNotification($details_admin));
                    }
                    $admin_3 = User::where('email', '=', 'sophie@wifetch.com')->first();
                    if($admin_3){
                        $admin_3->notify(new OrderPlacedNotification($details_admin));
                    }

                }

            }

//            if($notify_admin){
////                $admin_0 = User::where('email', '=', 'patosmack@gmail.com')->first();
////                if($admin_0){
////                    $admin_0->notify(new OrderPlacedNotification($details_admin));
////                }
//                $admin_1 = User::where('email', '=', 'lily@wifetch.com')->first();
//                if($admin_1){
//                    $admin_1->notify(new OrderPlacedNotification($details_admin));
//                }
//                $admin_2 = User::where('email', '=', 'lily@caribound.com')->first();
//                if($admin_2){
//                    $admin_2->notify(new OrderPlacedNotification($details_admin));
//                }
//                $admin_3 = User::where('email', '=', 'sophie@wifetch.com')->first();
//                if($admin_3){
//                    $admin_3->notify(new OrderPlacedNotification($details_admin));
//                }
//
//            }
        }



//
//        $details = [
//            'subject' => '',
//            'greeting' => 'Hi ' . $user->name,
//            'body' => 'Your order was placed successfully',
//            'thanks' => 'Thank you for using ' . env('APP_NAME'),
//            'actionText' => 'View your orders',
//            'actionURL' => route('account.orders'),
//            'order_id' => $order->id,
//        ];
//        $user->notify(new OrderPlacedNotification($details));

    }
}

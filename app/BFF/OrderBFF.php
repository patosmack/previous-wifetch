<?php

namespace App\BFF;

use App\Models\Order\OrderStatusLog;

class OrderBFF {
//
//$progressStatus = [
//'pending', 'waiting_for_payment', 'payment_approved', 'payment_rejected', 'payment_refunded', 'canceled', 'completed',
//'ready_to_fetch', 'finding_fetcher', 'fetching_order_items', 'ready_for_pickup',
//'ready_for_delivery','finding_delivery','delivery_on_place','collected_by_delivery',
//'transit_to_pickup','transit_to_destination','near_destination','delivered'
//];



    public static function step1Status($order){
        if($order->status === 'payment_approved'){
            return '';
        }
        if(in_array($order->status, [
            'pending', 'waiting_for_payment', 'payment_rejected', 'payment_refunded'
        ])){
            return 'disabled';
        }
        return 'complete';
    }

    public static function step2Status($order){
        if(self::step1Status($order) === 'complete'){
            if(in_array($order->status, [
                'ready_to_fetch', 'finding_fetcher', 'fetching_order_items'
            ])){
                return 'active';
            }
            if($order->status === 'ready_to_fetch'){
                return 'active';
            }
            return 'complete';
        }
        return 'disabled';
    }
    public static function step3Status($order){
        if(self::step2Status($order) === 'complete'){
            if(in_array($order->status, [
                'ready_for_delivery', 'finding_delivery', 'delivery_on_place', 'fetching_order_items'
            ])){
                return 'complete';
            }
            if($order->status === 'ready_for_pickup'){
                return 'active';
            }
            return 'complete';
        }
        return 'disabled';
    }

    public static function step4Status($order){
        if(self::step3Status($order) === 'complete'){
            if(in_array($order->status, [
                'delivery_on_place', 'transit_to_pickup', 'transit_to_destination', 'near_destination'
            ])){
                return 'complete';
            }
            if($order->status === 'ready_for_pickup'){
                return 'active';
            }
            return 'complete';
        }
        return 'disabled';
    }

    public static function step5Status($order){
        if(self::step4Status($order) === 'complete'){
            if($order->status === 'delivered'){
                return 'complete';
            }
        }
        return 'disabled';
    }
}

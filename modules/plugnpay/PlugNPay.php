<?php


class PlugNPay
{

    private $pnp_post_url = "https://pay1.plugnpay.com/payment/pnpremote.cgi";

    private $publisher_name, $publisher_password, $publisher_email;
    private $card_number, $card_cvv, $card_exp, $card_amount, $card_name;
    private $phone, $email, $ip_address;
    private $card_address1, $card_address2, $card_zip, $card_city, $card_province, $card_state, $card_country;
    private $shipname, $address1, $address2, $zip, $state, $country;

    private $authtype = 'authonly';
    private $prevOrderId;
    private $mode = 'auth';


    private $args = [];

    private $result = null;

    private $transactionId, $transactionStatus,
        $transactionMessage,
        $transactionDate,
        $transactionAmount,
        $transactionCurrency,
        $transactionSuccess,
        $transactionChargedAmount,
        $transactionCardType,
        $transactionCardName,
        $transactionCardNumber,
        $transactionCardExp,
        $transactionIsTest;


    public function __construct(){

        $this->publisher_name = env('PLUGNPAY_USERNAME');
        $this->publisher_password = env('PLUGNPAY_PASSWORD');
        $this->publisher_email = env('PLUGNPAY_EMAIL');


        $this->args = [
            'publisher-name' => $this->publisher_name,
            'publisher-password' => $this->publisher_password,
            'currency' => env('PLUGNPAY_CURRENCY'),
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
            'shipname' => $this->publisher_name,
            'client' => 'WiFetch',
            'dontsndmail' => 'yes',
           // 'convert'     => 'underscores',
            'easycart' => 0,
            'shipinfo' => 0,
            'paymethod' => 'credit',
        ];



//
//         shipping address info
//        $pnp_post_values .= "shipname=" . $shipname . "&";
//        $pnp_post_values .= "address1=" . $card_address1 . "&";
//        $pnp_post_values .= "address2=" . $card_address2 . "&";
//        $pnp_post_values .= "zip=" . $card_zip . "&";
//        $pnp_post_values .= "state=" . $card_state . "&";
//        $pnp_post_values .= "country=" . $card_country . "&";

    }

    public function setPublisherEmail($value){
        $this->publisher_email = $value;
        return $this;
    }

    public function setCardNumber($value){
        $this->card_number = $value;
        return $this;
    }

    public function setCardCVV($value){
        $this->card_cvv = $value;
        return $this;
    }

    public function setCardExp($value){
        $this->card_exp = $value;
        return $this;
    }

    public function setCardAmount($value){
        $this->card_amount = $value;
        return $this;
    }

    public function setCardName($value){
        $this->card_name = $value;
        return $this;
    }

    public function setIPAddress($value){
        $this->ip_address = $value;
        return $this;
    }

    public function setPhone($value){
        $this->phone = $value;
        return $this;
    }

    public function setEmail($value){
        $this->email = $value;
        return $this;
    }

    public function setCardAddress1($value){
        $this->card_address1 = $value;
        return $this;
    }

    public function setCardAddress2($value){
        $this->card_address2 = $value;
        return $this;
    }

    public function setCardZip($value){
        $this->card_zip = $value;
        return $this;
    }

    public function setCardCity($value){
        $this->card_city = $value;
        return $this;
    }

    public function setCardProvince($value){
        $this->card_province = $value;
        return $this;
    }

    public function setCardState($value){
        $this->card_state = $value;
        return $this;
    }

    public function setCardCountry($value){
        $this->card_country = $value;
        return $this;
    }

    public function setShipname($value){
        $this->shipname = $value;
        return $this;
    }

    public function setAddress1($value){
        $this->address1 = $value;
        return $this;
    }

    public function setAddress2($value){
        $this->address2 = $value;
        return $this;
    }

    public function setZip($value){
        $this->zip = $value;
        return $this;
    }

    public function setState($value){
        $this->state = $value;
        return $this;
    }

    public function setCountry($value){
        $this->country = $value;
        return $this;
    }


    //notify-email


    /**
     * Process a payment against the Plug n Pay payment gateway.
     *
     * <code>
     * $pnp = new PnP();
     * $response = $pnp->payment( $payment_values );
     * </code>
     *
     * Values expected to be in the payment array are:
     *
     */

    public function authorize(){

        $args = [
            'mode' => 'auth',
            'card-number' => $this->card_number,
            'card-cvv' => $this->card_cvv,
            'card-exp' => $this->card_exp,
            'card-amount' => $this->card_amount,
            'card-name' => $this->card_name,

            'card-address1' => $this->card_address1,
            'card-address2' => $this->card_address2,
            'card-zip' => $this->card_zip,
            'card-city' => $this->card_city,
            'card-state' => $this->card_state,
            'card-country' => $this->card_country,


            'email' => $this->email,
            'phone' => $this->phone,
//            'address1' => $this->address1,
//            'address2' => $this->address2,
//            'zip' => $this->zip,
//            'state' => $this->state,
//            'country' => $this->country,

            'authtype'    => 'authonly',
        ];

        return $this->excecute($args);

    }

    /**
     * Do a new authorizaction, using information from a previous
     * authorization, so that you don't need to know the payment information
     * again.
     *
     * You may explicitly pass the prevorderid in the argument list, or you
     * may create a PnP object with the orderID of the previous transaction
     * - which ever is more convenient.
     */

    public function authorizePrev($prevOrderId){

        $args = [
            'mode' => 'authprev',
            'card-amount' => $this->card_amount,
            'prevorderid'        => $prevOrderId,

        ];

        return $this->excecute($args);
    }

    /**
     * Cancels the most recent transaction operation of the given orderID.
     *
     * Mandatory argument 'card-amount', which must not exceed the amount of
     * the original transaction. You may wish to obtain that amount ahead of
     * time by calling $pnp->query_trans();
     * Optional argument 'notify-email', where email notification can be
     * sent for transaction problems.
     *
     * @param array Argument array
     * @return StdClass Return object
     */

    function refundLast( $args = array()) {

        $args = array_merge( $args, array(
            'mode'               => 'void',
            'orderID'            => $this->orderID,
            'txn-type'           => 'auth',
        ));

        return $this->pnp_results( $args );
    }

    private function build($args){
        $args = array_merge($this->args, $args);
        if ( !count($args)) {
            return null;
        }
        $urlQuery = null;
        foreach ($args as $key=>$val){
            if($val){
                if(!$urlQuery){
                    $urlQuery = $key . '=' . $val;
                }else{
                    $urlQuery .= '&'.$key . '=' . $val;
                }
            }
        }
        return $urlQuery;
    }

    private function excecute($args){

        $uriParameters = $this->build($args);

        $pnp_ch = curl_init($this->pnp_post_url);
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $uriParameters);
        curl_setopt($pnp_ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility

        // perform ssl post
        $pnp_result_page = curl_exec($pnp_ch);

        $pnp_result_decoded = urldecode($pnp_result_page);

        try{
            $pnp_temp_array = explode('&',$pnp_result_decoded);
            foreach ($pnp_temp_array as $entry) {
                list($name,$value) = explode('=',$entry);
                $pnp_transaction_array[$name] = $value;
            }
            return $pnp_transaction_array;
        }catch (\Throwable $exception){

        }
        throw new Exception('Unhandled Exception');
    }

    private function validateResponse(){

        if ($pnp_transaction_array['FinalStatus'] == "success") {
            return true;
        }
        elseif ($pnp_transaction_array['FinalStatus'] == "badcard") {
            throw new Exception('Bad Card Exception', 0);
        }
        elseif ($pnp_transaction_array['FinalStatus'] == "fraud") {
            throw new Exception('Fraud Exception', 1);
        }
        elseif ($pnp_transaction_array['FinalStatus'] == "problem") {
            throw new Exception('Transaction Problem Exception', 1);
        }
        throw new Exception('Unhandled Transaction Exception', 1);
    }


}

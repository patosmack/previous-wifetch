@extends('frontend.app')

@section('styles')

@endsection

@section('scripts')


@endsection

@section('content')

    <div class="wrapper">
        <div class="all-product-grid">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="order-placed-dt">
                            <i class="uil uil-times-circle icon-circle-red"></i>
                            <h2>The Order requested amount Could not be Placed</h2>
                            <p>Your payment was not approved</p>
                            <div class="delivery-address-bg">
                                <div class="stay-invoice-top">
                                    <div class="st-hm text-center w-100">Pay using another payment method</div>
                                </div>
                                <div class="stay-invoice">
                                    <a href="{{ route('extra_payment.view', $orderTransaction->transaction_id) }}" class="deliver-link">
                                        Go back to checkout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

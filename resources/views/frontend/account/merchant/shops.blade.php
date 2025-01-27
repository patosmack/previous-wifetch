@extends('frontend.app')

@section('content')

    <div class="wrapper">
        @include('frontend.shared.alert')
        <div class="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <h4><i class="uil uil-home"></i>My Shops</h4>
                                    </div>
                                </div>
                                @if(count($shops) === 0)

                                    <h4>Your shop is being  verified by our team, it will be available shortly, thank you for your patience</h4>

                                @else
                                @foreach($shops as $shopItem)
                                    <div class="col-lg-4 col-md-12">
                                        <div class="pdpt-bg">
                                            <a href="{{ route('account.merchant.shop', $shopItem->id) }}">
                                                <div class="reward-body-dtt">
                                                    <div class="reward-img-icon">
                                                        <img src="{{ asset($shopItem->logo) }}" alt="{{ $shopItem->name }}">
                                                    </div>
                                                    <span class="rewrd-title max-lines">{{ $shopItem->name }}</span>
                                                    <h4 class="cashbk-price">{{ $shopItem->products_count }} <span>Products</span></h4>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

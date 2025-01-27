@extends('frontend.app')

@section('styles')

    <link href="{{ asset('css/step-wizard.css') }}" rel="stylesheet">
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

        });
    </script>

@endsection

@section('content')

    <div class="theme-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb pl-1 pl-sm-0">
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('categories.list') }}">Categories</a> /</li>
                            <li class="breadcrumb-item d-none"><a href="{{ route('checkout.merchants') }}">Merchants</a> /</li>
                            <li class="breadcrumb-item">Checkout /</li>
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('checkout.address', $merchantCart['merchant']->friendly_url) }}">Address</a> /</li>
                            <li class="breadcrumb-item active">Timeframe</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper-breadcrumb">
        @include('frontend.shared.alert')
        <div class="all-product-grid">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-7">
                        <div id="checkout_wizard" class="checkout accordion left-chck145">
                            <div class="checkout-step">
                                <div class="checkout-card" id="headingTimeframe">
                                    <span class="checkout-step-number">2</span>
                                    <h4 class="checkout-step-title">
                                        <button class="wizard-btn" type="button" > Delivery Time & Date</button>
                                    </h4>
                                </div>
                                <div class="checkout-step-body">
                                    <form action="{{ route('checkout.timeframe.store', $merchant->friendly_url) }}" method="POST">
                                        <input type="hidden" value="{{ $merchant->id }}" name="merchant_id">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Select Date and Time *</label>
                                                    <div class="date-slider-group">
                                                        <div class="owl-carousel date-slider owl-theme">

                                                                @foreach($dates as $index => $dateItem)
                                                                    <div class="item">
                                                                        <div class="date-now">
                                                                            <input type="radio" id="date-{{ $index }}" value="{{ $index }}" name="date" @if(($dateItem->format('d/m/Y') === \Carbon\Carbon::now()->format('d/m/Y'))) checked="" @elseif((int)$index === (int)old('date', -1)) checked @endif>
                                                                            <label for="date-{{ $index }}">{{ $dateItem->format('d/m/Y') }}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                        </div>
                                                        @error('date')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

                                                    </div>
                                                    <div class="time-radio">
                                                        <div class="ui form">
                                                            <div class="grouped fields">

                                                                @if($merchant->deliveryTimeframes && count($merchant->deliveryTimeframes) > 0)
                                                                    @foreach($merchant->deliveryTimeframes as $timeframeItem)
                                                                        <div class="field">
                                                                            <div class="ui radio checkbox chck-rdio">
                                                                                <input type="radio" name="timeframe" value="{{ $timeframeItem->name }}"  @if((int)old('timeframe', $timeframes->first()->name) === (int)$timeframeItem->name) checked @endif tabindex="0" class="hidden">
                                                                                <label>{{ $timeframeItem->name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    @foreach($timeframes as $timeframeItem)
                                                                        <div class="field">
                                                                            <div class="ui radio checkbox chck-rdio">
                                                                                <input type="radio" name="timeframe" value="{{ $timeframeItem->name }}"  @if((int)old('timeframe', $timeframes->first()->name) === (int)$timeframeItem->name) checked @endif tabindex="0" class="hidden">
                                                                                <label>{{ $timeframeItem->name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif


                                                            </div>
                                                        </div>
                                                        @error('timeframe_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <p class="mb-3"><small>* The timeframe is attached to confirmation
                                                    </small></p>
                                            </div>

                                            @if($merchantCart['merchant']->disclaimer && strlen($merchantCart['merchant']->disclaimer) > 0)
                                                <div class="col-lg-12 col-md-12 mt-2 mb-5">
                                                    <h6 class="mt-0 pt-0 font-weight-bold">Disclaimer</h6>
                                                    <p style="font-size: 12px">{{ $merchantCart['merchant']->disclaimer }}</p>
                                                </div>
                                            @endif

                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <div class="address-btns">
                                                        <a href="{{ route('checkout.address', $merchantCart['merchant']->friendly_url) }}" class="save-btn14 hover-btn d-none d-sm-inline">Modify your address</a>
                                                        <a href="{{ route('checkout.address', $merchantCart['merchant']->friendly_url) }}" class="save-btn14 hover-btn d-inline d-sm-none">Back</a>
                                                        <button type="submit" class="ml-auto next-btn16 hover-btn"> Proccess to payment </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        @include('frontend.checkout.checkout_sidebar')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('frontend.app')


@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.28.0/moment-with-locales.min.js" integrity="sha512-kcvf1mExE8WCOLBL5re/9hLUHfaj8+LQrKlupTarmme+rwv8asLK4q6Ge32trTMBElPBP5sll4czZKNvps0VvA==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#open_time').datetimepicker({
                format: 'LT'
            });
            $('#close_time').datetimepicker({
                format: 'LT'
            });
        });
    </script>
@endsection

@section('content')

    <div id="delivery_timeframe_model" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>Delivery Timeframes</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-delivery_timeframe-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('account.merchant.delivery_timeframes.store') }}" method="POST">
                                        <input type="hidden" name="merchant_id" value="{{ $merchant->id }}" readonly required>
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-12">
                                                <legend class="col-form-label">Pick the start time and end time for the delivery window time frame</legend>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <hr>
                                                <legend class="col-form-label">Start Time</legend>
                                                <div class="form-group">
                                                    <div class="input-group date" id="open_time" data-target-input="nearest">
                                                        <input type="text"  name="open_time"  class="form-control datetimepicker-input" data-target="#open_time"/>
                                                        <div class="input-group-append" data-target="#open_time" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="uil uil-clock"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-sm-12">
                                                <legend class="col-form-label">End Time</legend>
                                                <div class="form-group">
                                                    <div class="input-group date" id="close_time" data-target-input="nearest">
                                                        <input type="text" name="close_time" class="form-control datetimepicker-input" data-target="#close_time"/>
                                                        <div class="input-group-append" data-target="#close_time" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="uil uil-clock"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <div class="address-btns">
                                                        <button type="submit" class="ml-auto next-btn16 hover-btn"> Save </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        @include('frontend.shared.alert')

        <div class="">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        @include('frontend.account.merchant.sidebar')
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <h4><i class="uil uil-location-point"></i>Business Delivery Timeframes</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>Manage your delivery timeframes</h4>
                                        </div>
                                        <div class="address-body">
                                            <a href="#" class="add-address hover-btn" data-toggle="modal" data-target="#delivery_timeframe_model" data-selected="delivery_timeframe-mon">Add New Delivery Timeframe</a>
                                            @foreach($merchant->deliveryTimeframes as $deliveryTimeframeItem)
                                                <div class="address-item d-flex align-items-center justify-content-between">
                                                    <div class="address-icon1">
                                                        <i class="uil uil-apps"></i>
                                                    </div>
                                                    <div class="address-dt-all w-100">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex flex-column">
                                                                <h4>{{ $deliveryTimeframeItem->name }}</h4>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <ul class="action-btns mt-0">
                                                                    <li><a href="{{ route('account.merchant.delivery_timeframes.delete', $deliveryTimeframeItem->id) }}" class="action-btn"><i class="uil uil-trash-alt"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

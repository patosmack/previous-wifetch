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

    <div id="available_hour_model" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>Available Hours</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-available_hour-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('backend.merchant.available_hours.store') }}" method="POST">
                                        <input type="hidden" name="merchant_id" value="{{ $merchant->id }}" readonly required>
                                        @csrf

                                       <div class="row">
                                           <div class="col-md-6">
                                               <legend class="col-form-label">Day of the week</legend>
                                               <div class="form-check">
                                                   <input class="form-check-input" type="radio" name="day" id="monday" value="1" checked>
                                                   <label class="form-check-label" for="monday">
                                                       Monday
                                                   </label>
                                               </div>
                                               <div class="form-check">
                                                   <input class="form-check-input" type="radio" name="day" id="tuesday" value="2">
                                                   <label class="form-check-label" for="tuesday">
                                                       Tuesday
                                                   </label>
                                               </div>
                                               <div class="form-check">
                                                   <input class="form-check-input" type="radio" name="day" id="wednesday" value="3">
                                                   <label class="form-check-label" for="wednesday">
                                                       Wednesday
                                                   </label>
                                               </div>
                                               <div class="form-check">
                                                   <input class="form-check-input" type="radio" name="day" id="thursday" value="4">
                                                   <label class="form-check-label" for="thursday">
                                                       Thursday
                                                   </label>
                                               </div>
                                           </div>
                                           <div class="col-md-6">
                                               <legend class="col-form-label">&nbsp;</legend>
                                               <div class="form-check">
                                                   <input class="form-check-input" type="radio" name="day" id="friday" value="5">
                                                   <label class="form-check-label" for="friday">
                                                       Friday
                                                   </label>
                                               </div>
                                               <div class="form-check">
                                                   <input class="form-check-input" type="radio" name="day" id="saturday" value="6">
                                                   <label class="form-check-label" for="saturday">
                                                       Saturday
                                                   </label>
                                               </div>
                                               <div class="form-check">
                                                   <input class="form-check-input" type="radio" name="day" id="sunday" value="0">
                                                   <label class="form-check-label" for="sunday">
                                                       Sunday
                                                   </label>
                                               </div>
                                           </div>
                                       </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <hr>
                                                <legend class="col-form-label">Open Time</legend>
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
                                                <legend class="col-form-label">Close Time</legend>
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        @include('backend.merchant.sidebar')
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <a href="{{ route('backend.merchant.products', $merchant->id) }}"><h4><i class="uil uil-arrow-circle-left"></i>Back to products</h4></a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>Manage your opening hours</h4>
                                        </div>
                                        <div class="address-body">
                                            <a href="#" class="add-address hover-btn" data-toggle="modal" data-target="#available_hour_model" data-selected="available_hour-mon">Add New Opening Hour</a>
                                            @foreach($merchant->availableHours as $availableHourItem)
                                                <div class="address-item d-flex align-items-center justify-content-between">
                                                    <div class="address-icon1">
                                                        <i class="uil uil-apps"></i>
                                                    </div>
                                                    <div class="address-dt-all w-100">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex flex-column">
                                                                <h4>{{ \App\Helpers\Helper::dayName($availableHourItem->day) }}</h4>
                                                                <p><small>Opening Time: {{ $availableHourItem->open_time }}</small></p>
                                                                <p><small>Closing Time: {{ $availableHourItem->close_time }}</small></p>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <ul class="action-btns mt-0">
                                                                    <li><a href="{{ route('backend.merchant.available_hours.delete', $availableHourItem->id) }}" class="action-btn"><i class="uil uil-trash-alt"></i></a></li>
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

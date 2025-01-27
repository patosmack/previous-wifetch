@extends('frontend.app')

@section('styles')

@endsection

@section('scripts')


@endsection

@section('content')

    <div class="wrapper">
        @include('frontend.shared.alert')
        <div class="all-product-grid">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="contact-title">
                            <h2>Become a Driver</h2>
                            <p>Have the freedom to choose when to work.</p>
                        </div>
                        <div class="contact-form">
                            <form  method="POST" action="{{ route('company.become_a_driver.store') }}">
                                @csrf
                                <h4 class="mb-4"><strong>Personal Information</strong></h4>
                                <div class="form-group mt-1">
                                    <label class="control-label">Full Name*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="name" id="name" value="{{ old('name') }}" required="" placeholder="Your Full Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Address*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="address" id="address" value="{{ old('address') }}" required="" placeholder="Your Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Email Address*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="email" name="email" id="email" value="{{ old('email') }}" required="" placeholder="Your Email Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Phone*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="phone" id="phone" value="{{ old('phone') }}" required="" placeholder="Your Phone">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 pb-2">
                                    <hr>
                                </div>


                                <h4 class="mb-4"><strong>Vehicle  Information</strong></h4>
                                <div class="form-group mt-1">
                                    <label class="control-label">Vehicle Licence Plate Number*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="vehicle_plate" id="vehicle_plate" value="{{ old('vehicle_plate') }}" required="" placeholder="E.g. A 4083">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Vehicle Brand*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="vehicle_brand" id="vehicle_brand" value="{{ old('vehicle_brand') }}" required="" placeholder="E.g. Kia">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Vehicle Model*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="email" name="vehicle_model" id="vehicle_model" value="{{ old('vehicle_model') }}" required="" placeholder="E.g. Moke">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Vehicle Year*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="email" name="vehicle_year" id="vehicle_year" value="{{ old('vehicle_year') }}" required="" placeholder="E.g. 2019">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Drivers Licence Number*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="license" id="license" value="{{ old('license') }}" required="" placeholder="Your Licence Number">
                                        </div>
                                    </div>
                                </div>

                                <button class="next-btn16 hover-btn mt-3" type="submit" data-btntext-sending="Sending...">Submit Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

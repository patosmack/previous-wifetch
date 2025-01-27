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
                    <div class="col-lg-6 col-md-6">
                        <div class="panel-group accordion" id="accordion0">
                            <div class="panel panel-default">
                                <div class="panel-heading" id="headingOne">
                                    <div class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-target="#collapseOne" href="#" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="uil uil-location-point chck_icon"></i>WIFETCH INC
                                        </a>
                                    </div>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion0" style="">
                                    <div class="panel-body">
                                        Suite 1, Durants<br>
                                        Christ Church<br>
                                        Barbados<br>
                                        <a href="tel:(246) 264-8994">Tel: (246) 264-8994</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="contact-title">
                            <h2>Contact Us</h2>
                            <p>Do you need help with a specific issue or just want to get in touch?</p>
                        </div>

                        <div class="contact-form">
                            <form  method="POST" action="{{ route('company.contact.store') }}">
                                @csrf
                                <div class="form-group mt-1">
                                    <label class="control-label">Full Name*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="name" id="name" value="{{ old('name') }}" required="" placeholder="Your Full Name">
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
                                    <div class="field">
                                        <label class="control-label">Message*</label>
                                        <textarea rows="2" class="form-control" id="message" name="message" required="" placeholder="Write us a Message">{{ old('message') }}</textarea>
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

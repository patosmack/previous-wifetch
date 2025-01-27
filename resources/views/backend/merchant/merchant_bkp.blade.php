@extends('frontend.app')


@section('scripts')

    <script>
        $(document).ready(function () {
            var allOptions = $('#parish_id option');
            var selectedOption = $('#parish_id').data('pre_selected');
            $('#parish_id option').remove();
            filterParishes();
            $('#country_id').change(function () {
                selectedOption = null;
                filterParishes();
            });

            function filterParishes() {
                $('#parish_id option').remove()
                var classN = $('#country_id option:selected').prop('class');;
                var opts = allOptions.filter('.' + classN);
                $('<option value="" selected disabled>Select one Parish</option>').appendTo('#parish_id');
                $.each(opts, function (i, j) {
                    $(j).appendTo('#parish_id');
                });
                if(selectedOption){
                    $("#parish_id").val(selectedOption);
                }else{
                    $("#parish_id").val($("#parish_id option:first").val());
                }
            }
        });
    </script>
@endsection

@section('content')

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
                                    <div class="main-title-tab d-flex flex-row justify-content-between align-items-center">
                                        <h4 class="d-flex"><i class="uil uil-box"></i>Merchant Detail</h4>
                                        <a href="{{ route('backend.merchant.list') }}" class="deliver-link m-0">Go Back</a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    @if($merchant)
                                        <div class="pdpt-bg">
                                            <div class="order-body10">
                                                <ul class="order-dtsll">
                                                    <li>
                                                        <div class="order-dt-img">
                                                            <img src="{{ $merchant->logo ? asset($merchant->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $merchant->name }}" class="img-fluid">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="order-dt47">
                                                            <h4>{{ $merchant->name }}</h4>
                                                            <div class="order-title">{{ count($merchant->products) }} Products/s</div>
                                                        </div>
                                                    </li>
                                                </ul>

                                                <hr>
                                                <div class="col-md-12">
                                                    <div class="pdpt-title">
                                                        <form method="POST" action="{{ route('backend.merchant.update', $merchant->id) }}" enctype="multipart/form-data">
                                                            <input type="hidden" value="save_order_total" name="target" readonly>
                                                            @csrf
                                                            <div class="row">

                                                                <div class="col-md-12 mb-3">
                                                                    <h3>Business Information</h3>
                                                                </div>

                                                                <div class="col-md-6">

                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Business Name*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="name" id="name" value="{{ old('name', $merchant->name) }}" required="" placeholder="The name of the business">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Category *</label>
                                                                        <select class="form-control" name="category_id" id="category_id" required="">
                                                                            <option value="" selected disabled >Select one Category</option>
                                                                            @foreach($categories as $categoryItem)
                                                                                <option value="{{ $categoryItem->id }}" @if((int)old('category_id', $merchant->category_id) === (int)$categoryItem->id) selected @endif>{{ $categoryItem->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('category_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Business Email Address</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="email" name="email" id="email" value="{{ old('email', $merchant->email) }}" placeholder="Email for contacting the Business">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Business Phone Number</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="phone" id="phone" value="{{ old('phone', $merchant->phone) }}" placeholder="Phone for contacting the Business">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group mt-1">
                                                                        <div class="field">
                                                                            <label class="control-label">Business Description</label>
                                                                            <textarea rows="2" class="form-control" id="description" name="description" placeholder="Please describe the nature of the business">{{ old('description', $merchant->description) }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12 mb-3">
                                                                    <hr>
                                                                    <h3>Location</h3>
                                                                </div>
                                                                <div class="col-md-6">

                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Country*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <select class="form-control" name="country_id" id="country_id" required="">
                                                                                    <option value="" selected class="empty" disabled>Select the Country</option>
                                                                                    @foreach($countries as $country)
                                                                                        <option value="{{ $country->id }}" class="{{ $country->iso }}" @if((int)old('country_id', $merchant->country_id) === (int)$country->id) selected @endif>{{ $country->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-6">

                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Parish*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <select class="form-control" name="parish_id" id="parish_id" required="" data-pre_selected="{{ old('parish_id', $merchant->parish_id) }}">
                                                                                    @foreach($countries as $country)
                                                                                        @foreach($country->parishes as $parish)
                                                                                            <option value="{{ $parish->id }}" class="selectors {{ $country->iso }}" @if((int)old('parish_id', $merchant->parish_id) === (int)$parish->id) selected @endif>{{ $parish->name }}</option>
                                                                                        @endforeach
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>









                                                                <div class="col-md-6">

                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Address*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="address" id="address" value="{{ old('address', $merchant->address ) }}" required="" placeholder="Your Address">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12 mb-3">
                                                                    <hr>
                                                                    <h3>Contact</h3>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Internal Contact Name</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $merchant->contact_name) }}" placeholder="Name of the Contact Person">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Internal Contact Email Address</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $merchant->contact_email) }}" placeholder="Email Address for us to contact">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Internal Contact Phone</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $merchant->contact_phone) }}" placeholder="Phone Number for us to contact">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <hr>
                                                                    <h3>Logo</h3>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="logo">Upload logo</label>
                                                                        <input type="file" class="form-control-file" id="attachment" name="attachment" accept="image/*">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mt-5 mb-5">
                                                                    <button class="login-btn hover-btn" type="submit">Update information</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

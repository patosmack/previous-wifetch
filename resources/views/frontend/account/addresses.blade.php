@extends('frontend.app')


@section('scripts')

    <script>
        $(document).ready(function () {
            var allOptions = $('#parish_id option');
            var selectedOption = $('#parish_id').data('pre_selected');
            $('#parish_id option').remove();
            $('<option value="" selected disabled>Select one Country first</option>').appendTo('#parish_id');
            if(selectedOption){
                filterParishes();
            }
            $('#country_id').change(function () {
                selectedOption = null;
                filterParishes();
            });
            function filterParishes() {
                $('#parish_id option').remove()
                var classN = $('#country_id option:selected').prop('class');;
                var opts = allOptions.filter('.' + classN);
                $.each(opts, function (i, j) {
                    $(j).appendTo('#parish_id');
                });
                if(selectedOption){
                    $("#parish_id").val(selectedOption);
                }else{
                    $("#parish_id").val($("#parish_id option:first").val());
                }
            }

            pickerFill($('.address_picker_selected'));

            $('.address_picker').change(function () {
                pickerFill(this);
            });

            function pickerFill(obj) {
                let old = $(obj).data('old');
                if(!old){
                    let name = $(obj).data('name');
                    let countryid = $(obj).data('countryid');
                    let parishid = $(obj).data('parishid');
                    let address = $(obj).data('address');
                    let phone = $(obj).data('phone');
                    let instructions = $(obj).data('instructions');

                    $('#name').val(name);
                    $('#address').val(address);
                    $('#phone').val(phone);
                    $('#instructions').val(instructions);

                    $("#country_id").val(countryid);
                    filterParishes();
                    setTimeout(function() {
                        $("#parish_id").val(parishid);
                    }, 5);
                }
            }

            $('#address_model').on('shown.bs.modal', function (e) {
                let selected = $(e.relatedTarget).data('selected');
                if(selected){
                    let sel = $(selected);
                    sel.prop( "checked", true );
                    pickerFill(sel);
                }
            })

        });
    </script>

@endsection

@section('content')

    <div id="address_model" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>Addresses</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-address-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('account.addresses.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="product-radio">
                                                <ul class="product-now">
                                                    @foreach($userAccount->addresses as $userAddressItem)
                                                        <li>

                                                            <input
                                                                class="address_picker
                                                                @if(old('user_address_id'))
                                                                    @if(old('user_address_id') === $userAddressItem->id)
                                                                        address_picker_selected
                                                                    @endif
                                                                @else
                                                                    @if($userAddressItem->current)
                                                                        address_picker_selected
                                                                    @endif
                                                                @endif
                                                                    "
                                                                type="radio"
                                                                id="address-{{ $userAddressItem->id }}"
                                                                name="user_address_id"
                                                                data-old="{{ !!old('merchant_id') }}"
                                                                value="{{ $userAddressItem->id }}"
                                                                data-name="{{ ($userAddressItem->name  && strlen(trim($userAddressItem->name) )) > 0 ? $userAddressItem->name : 'Primary' }}"
                                                                data-countryid="{{ $userAddressItem->country_id ? $userAddressItem->country_id : ( $userAddressItem->parish ? $userAddressItem->parish->country_id : '' ) }}"
                                                                data-parishid="{{ $userAddressItem->parish ? $userAddressItem->parish->id : '' }}"
                                                                data-address="{{ $userAddressItem->address }}"
                                                                data-phone="{{ $userAddressItem->phone ? $userAddressItem->phone : $userAccount->phone }}"
                                                                data-instructions="{{ $userAddressItem->instructions }}"

                                                                @if(old('user_address_id'))
                                                                @if(old('user_address_id') === $userAddressItem->id)
                                                                checked
                                                                @endif
                                                                @else
                                                                @if($userAddressItem->current)
                                                                checked
                                                                @endif
                                                                @endif
                                                            >
                                                            <label for="address-{{ $userAddressItem->id }}">{{ ($userAddressItem->name  && strlen(trim($userAddressItem->name) )) > 0 ? $userAddressItem->name : 'Primary' }}</label>
                                                        </li>
                                                    @endforeach
                                                    <li>
                                                        <input
                                                            class="address_picker"
                                                            type="radio"
                                                            id="address-new"
                                                            value=""
                                                            name="user_address_id"
                                                            data-name=""
                                                            data-country=""
                                                            data-countryid=""
                                                            data-parish=""
                                                            data-parishid=""
                                                            data-address=""
                                                            data-phone=""
                                                            data-instructions=""
                                                        >
                                                        <label for="address-new" class="bg-success">New address</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Name*</label>
                                                        <input id="name" name="name" value="{{ old('name') }}" type="text" placeholder="E.g. 'Home', 'Work' or 'Office'" class="form-control input-md" required="">
                                                        @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Phone*</label>
                                                        <input id="phone" name="phone" value="{{ old('phone') }}" type="text" placeholder="Your contact phone number" class="form-control input-md" required="">
                                                        @error('phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Country*</label>
                                                        <div class="ui search focus">
                                                            <div class="ui left icon input swdh11 swdh19">
                                                                <select class="form-control" name="country_id" id="country_id" required="">
                                                                    <option value="" selected class="empty" disabled>Select your Country</option>
                                                                    @foreach($countries as $country)
                                                                        <option value="{{ $country->id }}" class="{{ $country->iso }}" @if((int)old('country_id') === (int)$country->id) selected @endif>{{ $country->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @error('country_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Parish*</label>
                                                        <div class="ui search focus">
                                                            <div class="ui left icon input swdh11 swdh19">
                                                                <select class="form-control" name="parish_id" id="parish_id" data-pre_selected="{{ old('parish_id') }}" required="">
                                                                    @foreach($countries as $country)
                                                                        @foreach($country->parishes as $parish)
                                                                            <option value="{{ $parish->id }}" class="selectors {{ $country->iso }}" @if((int)old('parish_id') === (int)$parish->id) selected @endif>{{ $parish->name }}</option>
                                                                        @endforeach
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @error('parish_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Address*</label>
                                                        <input id="address" name="address" value="{{ old('address') }}" type="text" placeholder="Delivery Address" class="form-control input-md" required="">
                                                        @error('address')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Instructions</label>
                                                        <textarea class="form-control" name="instructions" id="instructions" placeholder="Some tips to find your address location"  rows="3">{{ old('instructions') }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <div class="address-btns">
                                                            <button type="submit" class="ml-auto next-btn16 hover-btn"> Save </button>
                                                        </div>
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
                        @include('frontend.account.sidebar')
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <h4><i class="uil uil-location-point"></i>My Address</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>My Address</h4>
                                        </div>
                                        <div class="address-body">
                                            <a href="#" class="add-address hover-btn" data-toggle="modal" data-target="#address_model" data-selected="#address-new">Add New Address</a>
                                            @foreach($userAccount->addresses as $userAddressItem)
                                                <div class="address-item d-flex align-items-center justify-content-between">
                                                    <div class="address-icon1">
                                                        <i class="uil uil-map-marker"></i>
                                                    </div>
                                                    <div class="address-dt-all w-100">


                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex flex-column">
                                                                <h4 class="mt-0">{{ $userAddressItem->name }}</h4>
                                                                <p>{{ $userAddressItem->address }} @if($userAddressItem->parish), {{ $userAddressItem->parish->name }} @endif @if($userAddressItem->country) - {{ $userAddressItem->country->name }} @endif</p>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <ul class="action-btns mt-0">
                                                                    <li><a href="#" class="action-btn" data-toggle="modal" data-target="#address_model" data-selected="#address-{{ $userAddressItem->id }}"><i class="uil uil-edit"></i></a></li>
                                                                    <li><a href="{{ route('account.addresses.delete', $userAddressItem->id) }}" class="action-btn"><i class="uil uil-trash-alt"></i></a></li>
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

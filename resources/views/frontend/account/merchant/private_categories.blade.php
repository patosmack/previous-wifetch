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

            pickerFill($('.private_category_picker_selected'));

            $('.private_category_picker').change(function () {
                pickerFill(this);
            });

            function pickerFill(obj) {
                let old = $(obj).data('old');
                if(!old){
                    let name = $(obj).data('name');
                    let countryid = $(obj).data('countryid');
                    let parishid = $(obj).data('parishid');
                    let private_category = $(obj).data('private_category');
                    let phone = $(obj).data('phone');
                    let instructions = $(obj).data('instructions');

                    $('#name').val(name);
                    $('#private_category').val(private_category);
                    $('#phone').val(phone);
                    $('#instructions').val(instructions);

                    $("#country_id").val(countryid);
                    filterParishes();
                    setTimeout(function() {
                        $("#parish_id").val(parishid);
                    }, 5);
                }
            }

            $('#private_category_model').on('shown.bs.modal', function (e) {
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

    <div id="private_category_model" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>Private Categories</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-private_category-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('account.merchant.private_categories.store') }}" method="POST">
                                        <input type="hidden" name="merchant_id" value="{{ $merchant->id }}" readonly required>
                                        @csrf
                                        <div class="form-group">
                                            <div class="product-radio">
                                                <ul class="product-now">
                                                    @foreach($merchant->privateCategories as $privateCategoryItem)
                                                        <li>
                                                            <input
                                                                class="address_picker
                                                                @if(old('private_category_id'))
                                                                    @if(old('private_category_id') === $privateCategoryItem->id)
                                                                        private_category_picker_selected
                                                                    @endif
                                                                @else
                                                                    @if($privateCategoryItem->current)
                                                                        private_category_picker_selected
                                                                    @endif
                                                                @endif
                                                                    "
                                                                type="radio"
                                                                id="private_category-{{ $privateCategoryItem->id }}"
                                                                name="private_category_id"
                                                                data-old="{{ !!old('private_category_id') }}"
                                                                value="{{ $privateCategoryItem->id }}"
                                                                data-name="{{ $privateCategoryItem->name }}"
                                                                data-countryid="{{ $privateCategoryItem->country_id ? $privateCategoryItem->country_id : ( $privateCategoryItem->parish ? $privateCategoryItem->parish->country_id : '' ) }}"
                                                                data-parishid="{{ $privateCategoryItem->parish ? $privateCategoryItem->parish->id : '' }}"
                                                                data-private_category="{{ $privateCategoryItem->private_category }}"
                                                                data-phone="{{ $privateCategoryItem->phone ? $privateCategoryItem->phone : $merchant->phone }}"
                                                                data-instructions="{{ $privateCategoryItem->instructions }}"

                                                                @if(old('private_category_id'))
                                                                @if(old('private_category_id') === $privateCategoryItem->id)
                                                                checked
                                                                @endif
                                                                @else
                                                                @if($privateCategoryItem->current)
                                                                checked
                                                                @endif
                                                                @endif
                                                            >
                                                            <label for="private_category-{{ $privateCategoryItem->id }}">{{ $privateCategoryItem->name }}</label>
                                                        </li>
                                                    @endforeach
                                                    <li>
                                                        <input
                                                            class="address_picker"
                                                            type="radio"
                                                            id="private_category-new"
                                                            value=""
                                                            name="private_category_id"
                                                            data-name=""
                                                            data-country=""
                                                            data-countryid=""
                                                            data-parish=""
                                                            data-parishid=""
                                                            data-private_category=""
                                                            data-phone=""
                                                            data-instructions=""
                                                        >
                                                        <label for="private_category-new" class="bg-success">New Category</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Name*</label>
                                                        <input id="name" name="name" value="{{ old('name') }}" type="text" placeholder="E.g. 'Tea & Cocoa', 'Personal Care' or 'Flavored Water'" class="form-control input-md" required="">
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
                        @include('frontend.account.merchant.sidebar')
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <h4><i class="uil uil-location-point"></i>Business Categories</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>Manage your business categories</h4>
                                        </div>
                                        <div class="address-body">
                                            <a href="#" class="add-address hover-btn" data-toggle="modal" data-target="#private_category_model" data-selected="private_category-new">Add New Category</a>
                                            @foreach($merchant->privateCategories as $privateCategoryItem)
                                                <div class="address-item d-flex align-items-center justify-content-between">
                                                    <div class="address-icon1">
                                                        <i class="uil uil-apps"></i>
                                                    </div>
                                                    <div class="address-dt-all w-100">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex flex-column">
                                                                <h4>{{ $privateCategoryItem->name }}</h4>
                                                                <p><small>Products: {{ $privateCategoryItem->product_count }}</small></p>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <ul class="action-btns mt-0">
                                                                    <li><a href="#" class="action-btn" data-toggle="modal" data-target="#private_category_model" data-selected="#private_category-{{ $privateCategoryItem->id }}"><i class="uil uil-edit"></i></a></li>
                                                                    <li><a href="{{ route('account.merchant.private_categories.delete', $privateCategoryItem->id) }}" class="action-btn"><i class="uil uil-trash-alt"></i></a></li>
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
{{--                    <div class="col-lg-9 col-md-8">--}}
{{--                        <div class="dashboard-right">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <div class="main-title-tab">--}}
{{--                                        <h4><i class="uil uil-location-point"></i>Business Categories</h4>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-12 col-md-12">--}}
{{--                                    <div class="pdpt-bg">--}}
{{--                                        <div class="pdpt-title">--}}
{{--                                            <h4>Manage your internal business categories</h4>--}}
{{--                                        </div>--}}
{{--                                        <div class="address-body">--}}
{{--                                            <a href="#" class="add-address hover-btn" data-toggle="modal" data-target="#private_category_model" data-selected="#private_category-new">Add New private_category</a>--}}
{{--                                            @foreach($merchant->privateCategories as $privateCategoryItem)--}}
{{--                                                <div class="address-item">--}}
{{--                                                    <div class="address-icon1">--}}
{{--                                                        <i class="uil uil-home-alt"></i>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="address-dt-all">--}}
{{--                                                        <h4>{{ $privateCategoryItem->name }}</h4>--}}
{{--                                                        <p>{{ $privateCategoryItem->private_category }} @if($privateCategoryItem->parish), {{ $privateCategoryItem->parish->name }} @endif @if($privateCategoryItem->country) - {{ $privateCategoryItem->country->name }} @endif</p>--}}
{{--                                                        <ul class="action-btns">--}}
{{--                                                            <li><a href="#" class="action-btn" data-toggle="modal" data-target="#private_category_model" data-selected="#private_category-{{ $privateCategoryItem->id }}"><i class="uil uil-edit"></i></a></li>--}}
{{--                                                            <li><a href="{{ route('account.privateCategories.delete', $privateCategoryItem->id) }}" class="action-btn"><i class="uil uil-trash-alt"></i></a></li>--}}
{{--                                                        </ul>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

@endsection

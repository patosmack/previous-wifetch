@extends('frontend.app')


@section('scripts')

    <script>
        $(document).ready(function () {
            $('#backend_category_modal').on('shown.bs.modal', function (e) {

                let target = $(e.relatedTarget).data('target');
                let name = $(e.relatedTarget).data('name');
                let friendlyurl = $(e.relatedTarget).data('friendlyurl');
                let icon = $(e.relatedTarget).data('icon');
                let cover = $(e.relatedTarget).data('cover');
                let order = $(e.relatedTarget).data('order');
                let id = $(e.relatedTarget).data('id');


                $('#backend_category_name').val(name)
                $('#backend_category_friendly_url').val(friendlyurl)
                $('#backend_category_order').val(order)
                $('#backend_category_id').val(id)
                if(icon.length > 0){
                    $('#backend_cateogry_icon').attr({ src: icon});
                }
            })

        });
    </script>

@endsection

@section('content')

    <div id="backend_category_modal" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>Site Categories</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-category-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('backend.category.store') }}" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" id="backend_category_id" value="" name="category_id">
                                        @csrf
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Name*</label>
                                                        <input id="backend_category_name" name="name" value="{{ old('name') }}" type="text" placeholder="E.g. 'WiShopping, WiHealth'" class="form-control input-md" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Friendly URL*</label>
                                                        <input id="backend_category_friendly_url" name="friendly_url" value="{{ old('friendly_url') }}" type="text" placeholder="E.g. 'shopping, health'" class="form-control input-md" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Sort Order*</label>
                                                        <input id="backend_category_order" name="order" value="{{ old('order') }}" type="text" placeholder="E.g. 0, 1, 2, ... 4" class="form-control input-md" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mt-1 mb-2">
                                                        <label for="icon" class="control-label">Category Icon</label>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset('assets/common/image_placeholder.png') }}" id="backend_cateogry_icon" alt="" class="img-fluid p-3 mr-3" width="100">

                                                            <input type="file" class="form-control-file" name="icon" id="icon">
                                                        </div>
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
                                        <h4><i class="uil uil-location-point"></i>Categories</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>Manage your site categories</h4>
                                        </div>
                                        <div class="address-body">
                                            <a href="#"
                                               class="add-address hover-btn"
                                               data-toggle="modal"
                                               data-target="#backend_category_modal"
                                               data-name=""
                                               data-friendlyurl=""
                                               data-icon="{{ asset('assets/common/image_placeholder.png') }}"
                                               data-cover=""
                                               data-order=""
                                               data-id="">
                                                Add New Category
                                            </a>

                                            @foreach($siteCategories as $siteCategoryItem)
                                                <div class="address-item d-flex align-items-center justify-content-between">
                                                    <img src="{{ asset($siteCategoryItem->icon) }}" alt="" width="38" class="mr-4">
                                                    <div class="address-dt-all w-100">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex flex-column">
                                                                <h4>{{ $siteCategoryItem->name }}</h4>
                                                                <p><small>Merchants: {{ $siteCategoryItem->merchants_count }}</small></p>
                                                                <p><small>Status: @if($siteCategoryItem->enabled) <strong>Enabled</strong> @else <strong class="text-danger">Disabled</strong> @endif</small></p>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <ul class="action-btns mt-0">
                                                                    <li>
                                                                        <a href="#"
                                                                           class="action-btn"
                                                                           data-toggle="modal"
                                                                           data-target="#backend_category_modal"
                                                                           data-name="{{ $siteCategoryItem->name }}"
                                                                           data-friendlyurl="{{ $siteCategoryItem->friendly_url }}"
                                                                           data-icon="{{ asset($siteCategoryItem->icon) }}"
                                                                           data-cover="{{ asset( $siteCategoryItem->cover)  }}"
                                                                           data-order="{{ $siteCategoryItem->order }}"
                                                                           data-id="{{ $siteCategoryItem->id }}">
                                                                            <i class="uil uil-edit"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('backend.category.toggle.status', $siteCategoryItem->id) }}" class="action-btn" title="@if($siteCategoryItem->enabled) Disable the category @else Enable the category @endif">
                                                                            @if($siteCategoryItem->enabled)
                                                                                <i class="uil uil-trash-alt"></i>
                                                                            @else
                                                                                <i class="uil uil-check"></i>
                                                                            @endif
                                                                        </a>
                                                                    </li>
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

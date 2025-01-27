@extends('frontend.app')


@section('scripts')

    <script>
        $(document).ready(function () {
            $('#backend_banner_modal').on('shown.bs.modal', function (e) {

                let target = $(e.relatedTarget).data('targeturl');
                let name = $(e.relatedTarget).data('name');
                let image = $(e.relatedTarget).data('image');
                let imagemobile = $(e.relatedTarget).data('imagemobile');
                let order = $(e.relatedTarget).data('order');
                let id = $(e.relatedTarget).data('id');

                $('#backend_banner_name').val(name)
                $('#backend_banner_target').val(target)
                $('#backend_banner_order').val(order)
                $('#backend_banner_id').val(id)
                if(image.length > 0){
                    $('#backend_banner_image').attr({ src: image});
                }
                if(imagemobile.length > 0){
                    $('#backend_banner_image_mobile').attr({ src: imagemobile});
                }

            })

        });
    </script>

@endsection

@section('content')

    <div id="backend_banner_modal" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog banner-area" role="document">
            <div class="banner-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="banner-model-content modal-content">
                    <div class="cate-header">
                        <h4>Home Banners</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-banner-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('backend.banner.store') }}" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" id="backend_banner_id" value="" name="banner_id">
                                        @csrf
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Name*</label>
                                                        <input id="backend_banner_name" name="name" value="{{ old('name') }}" type="text" placeholder="E.g. 'WiShopping, WiHealth'" class="form-control input-md" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Target URL*</label>
                                                        <input id="backend_banner_target" name="target" value="{{ old('target') }}" type="text" placeholder="E.g. 'https://wifetch.com'" class="form-control input-md" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Sort Order*</label>
                                                        <input id="backend_banner_order" name="order" value="{{ old('order') }}" type="text" placeholder="E.g. 0, 1, 2, ... 4" class="form-control input-md" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mt-1 mb-2">
                                                        <label for="icon" class="control-label">Banner Desktop Image</label>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset('assets/common/image_placeholder.png') }}" id="backend_banner_image" alt="" class="img-fluid p-3 mr-3" width="100">
                                                            <input type="file" class="form-control-file" name="image" id="image">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mt-1 mb-2">
                                                        <label for="icon" class="control-label">Banner Mobile Image</label>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset('assets/common/image_placeholder.png') }}" id="backend_banner_image_mobile" alt="" class="img-fluid p-3 mr-3" width="100">
                                                            <input type="file" class="form-control-file" name="image_mobile" id="image_mobile">
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
                                        <h4><i class="uil uil-location-point"></i>Banners</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>Manage your site banners</h4>
                                        </div>
                                        <div class="address-body">
                                            <a href="#"
                                               class="add-address hover-btn"
                                               data-toggle="modal"
                                               data-target="#backend_banner_modal"
                                               data-name=""
                                               data-targeturl=""
                                               data-image="{{ asset('assets/common/image_placeholder.png') }}"
                                               data-imagemobile="{{ asset('assets/common/image_placeholder.png') }}"
                                               data-order=""
                                               data-id="">
                                                Add New Banner
                                            </a>

                                            @foreach($siteBanners as $siteBannerItem)
                                                <div class="address-item d-flex align-items-center justify-content-between">
                                                    <img src="{{ asset($siteBannerItem->image) }}" alt="" height="60" class="mr-4">
                                                    <div class="address-dt-all w-100">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex flex-column">
                                                                <h4>{{ $siteBannerItem->name }}</h4>
                                                                <p><small>Target: {{ $siteBannerItem->target }}</small></p>
                                                                <p><small>Status: @if($siteBannerItem->enabled) <strong>Enabled</strong> @else <strong class="text-danger">Disabled</strong> @endif</small></p>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <ul class="action-btns mt-0">
                                                                    <li>
                                                                        <a href="#"
                                                                           class="action-btn"
                                                                           data-toggle="modal"
                                                                           data-target="#backend_banner_modal"
                                                                           data-name="{{ $siteBannerItem->name }}"
                                                                           data-targeturl="{{ $siteBannerItem->target }}"
                                                                           data-image="{{ asset($siteBannerItem->image) }}"
                                                                           data-imagemobile="{{ asset( $siteBannerItem->image_mobile)  }}"
                                                                           data-order="{{ $siteBannerItem->order }}"
                                                                           data-id="{{ $siteBannerItem->id }}">
                                                                            <i class="uil uil-edit"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('backend.banner.toggle.status', $siteBannerItem->id) }}" class="action-btn" title="@if($siteBannerItem->enabled) Disable the banner @else Enable the banner @endif">
                                                                            @if($siteBannerItem->enabled)
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

@extends('frontend.app')


@section('scripts')

    <script>
        $(document).ready(function () {
            $('#backend_import_modal').on('shown.bs.modal', function (e) {

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

    <div id="backend_import_modal" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>Import Merchant Products</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-category-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('backend.import.store') }}" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" id="merchant_id" value="{{ $merchant_id }}" name="merchant_id">
                                        @csrf
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Description*</label>
                                                        <input id="description" name="description" value="{{ old('description') }}" type="text" placeholder="E.g. 'New Product List'" class="form-control input-md" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mt-1 mb-2">
                                                        <label for="icon" class="control-label">Excel File</label>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset('assets/common/xlsx-512.png') }}" id="attachment_file" alt="" class="img-fluid p-3 mr-3" width="100">
                                                            <input type="file" class="form-control-file" name="attachment" id="attachment">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Download Sample File</label>
                                                        <a href="{{ asset('assets/importer_sample/product_import_sample.xlsx') }}">From Here</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <div class="address-btns">
                                                            <button type="submit" class="ml-auto next-btn16 hover-btn"> Upload </button>
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
                                        <h4><i class="uil uil-location-point"></i>Files</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>Manage your merchant import files</h4>
                                        </div>
                                        <div class="address-body">
                                            <a href="#"
                                               class="add-address hover-btn"
                                               data-toggle="modal"
                                               data-target="#backend_import_modal"
                                               data-description=""
                                               data-friendlyurl=""
                                               data-icon="{{ asset('assets/common/xlsx-512.png') }}"
                                               data-cover=""
                                               data-order=""
                                               data-id="">
                                                Add new file
                                            </a>

                                            @foreach($merchantImports as $import)
                                                <div class="address-item d-flex align-items-center justify-content-between">
                                                    <div class="address-dt-all w-100">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex flex-column w-100">
                                                                <h3 class="pb-0 mb-0">{{ $import->description }}</h3>
                                                                <h5 class="mt-1 @if($import->status === 'processed') font-weight-bold text-info @endif" @if($import->status === 'processed') style="font-size: 18px" @endif>{{ $import->status_message }}</h5>
                                                                <p><small>Filename: {{ str_replace('import_histories/', '', $import->file_name)  }}</small></p>
                                                                <hr>
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

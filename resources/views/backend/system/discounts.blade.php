@extends('frontend.app')

@section('styles')
    <link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let table = $('#list-datatable').DataTable({
                columnDefs: [
                    {targets: 1, orderable: true},
                    {targets: 2, orderable: true},
                    {targets: 3, orderable: true},
                    {targets: 4, orderable: true},
                    // {targets: 5, orderable: true},
                    // {targets: 6, orderable: true},
                ],
                columns : [
                    {
                        title: "ID", data: "id",
                        render: function ( data, type, row ) {
                            return '<span class="table-text small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Code", data: "code",
                        render: function ( data, type, row ) {
                            return '<div><input type="text" value="'+data.value+'" data-ref="'+data.id+'" data-message="#code_message'+data.id+'" data-target="code" class="table-input dynamicEditor">' +
                                '<p class="text-left">' +
                                '<small id="code_message'+data.id+'" class="d-none"></small></p></div>';
                        }
                    },
                    {
                        title: "Rate", data: "rate",
                        render: function ( data, type, row ) {

                            return '<div class="input-group mb-3">\n' +
                                '  <input type="number" step="1" min="1" value="'+data.value+'" data-ref="'+data.id+'" data-message="#rate_message'+data.id+'" data-target="rate" class="table-input dynamicEditor">\n' +
                                '  <div class="input-group-append">\n' +
                                '    <span class="input-group-text table-input-text-regular"> OFF</span>\n' +
                                '  </div>\n' +
                                '<p class="text-left d-block w-100"><small id="rate_message'+data.id+'" class="d-none table-input-text-small-block mt-1"></small></p>' +
                                '</div>';
                        }
                    },
                    {
                        title: "One time use", data: "consumable",
                        render: function ( data, type, row ,obj) {
                            if(data.value){
                                return '<span class="table-text small">YES</span>';
                            }
                            return '<span class="table-text small">NO</span>';
                        }
                    },
                    // {
                    //     title: "Quantity", data: "quantity",
                    //     render: function ( data, type, row ) {
                    //         return '<div><input type="number" step="1" min="1" value="'+data.value+'" data-ref="'+data.id+'" data-message="#quantity_message'+data.id+'" data-target="quantity" class="table-input dynamicEditor">' +
                    //             '<p class="text-center">' +
                    //             '<small id="quantity_message'+data.id+'" class="d-none"></small></p></div>';
                    //     }
                    // },

            //         <select class="form-control" id="is_percentage" name="is_percentage">
            //     <option value="0" selected>Fixed money amount</option>
            // <option value="1">Percentage</option>
            //     </select>

                    {
                        title: "Discount Type", data: "is_percentage",
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="enabled" data-ref="'+data.id+'" data-target="is_percentage" data-message="#is_percentage_message'+data.id+'" class="table-select dynamicEditor" >'
                            if(data.value){
                                select += '<option value="0">Fixed money amount</option>';
                                select += '<option value="1" selected>Percentage</option>';
                            }else{
                                select += '<option value="0 selected">Fixed money amount</option>';
                                select += '<option value="1">Percentage</option>';
                            }
                            select += '</select><small id="is_percentage_message'+data.id+'" class="d-none"></small></div>';
                            return select;
                        }
                    },
                    {
                        title: "Enabled", data: "enabled",
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="enabled" data-ref="'+data.id+'" data-target="enabled" data-message="#enabled_message'+data.id+'" class="table-select dynamicEditor" >'
                            if(data.value){
                                select += '<option value="0">No</option>';
                                select += '<option value="1" selected>Yes</option>';
                            }else{
                                select += '<option value="0 selected">No</option>';
                                select += '<option value="1">Yes</option>';
                            }
                            select += '</select><small id="enabled_message'+data.id+'" class="d-none"></small></div>';
                            return select;
                        }
                    },
                    {
                        title: "Status", data: "status",
                        render: function ( data, type, row ) {
                            if(data.status){
                                return '<span class="badge badge-success">'+data.value+'</span>';
                            }
                            return '<span class="badge badge-danger">'+data.value+'</span>';
                        }
                    },
                ],
                serverSide: true,
                paging: true,
                dom: 'l<"toolbar">frtip',
                stateSave: true,
                lengthMenu: [ 25, 50, 100, 200],
                ajax:{
                    type: "POST",
                    url: "{{ route('backend.discount.json', (isset($status) && $status) ? $status : null) }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType: "json",
                    error: function (request, error) {
                        console.log(arguments);
                        alert(" Can't do because: " + error);
                    },
                }

            });

            generateStatusFilterToolbar();

            function generateStatusFilterToolbar(){
                let catObj = $('#status_form').clone().removeClass('d-none');
                $("#list-datatable_filter").append(catObj);
                $('#status_form').remove();
            }

            $(document).on( "change", "#status_select", function(e){
                $('#status_form').submit();
            });

            $('#refresh_on_change').prop('checked', (localStorage.getItem('refresh_on_chante_checkbox') === 'true'? true: false) || false);

            $(document).on( "change", "#refresh_on_change", function(e){
                localStorage.setItem("refresh_on_chante_checkbox", $(this).is(":checked"));
            });

            var timer;
            var doneTypingInterval = 500;
            $(document).on( "change keyup keydown", ".dynamicEditor", function(e){
                if(e.type === 'keyup') {
                    clearTimeout(timer);
                    e.preventDefault();
                    let obj = this;
                    timer = setTimeout(function () {
                        $(obj).trigger('change');
                    }, doneTypingInterval);
                    return false;
                }else if(e.type === 'keydown'){
                    clearTimeout(timer);
                }else{
                    let target = $(this).data('target');
                    let ref = $(this).data('ref');
                    let message = $(this).data('message');
                    let val = $(this).val();
                    sendRequest(this, target, ref, val, message);
                }
            });

            function sendRequest(element, target, ref, val, message) {
                $(message).removeClass("d-none text-danger text-success text-info");
                $.ajax({
                    data:  {target,id:ref, value:val},
                    url:   '{{ route('backend.discount.easy-edit') }}',
                    type:  'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $(message).addClass('text-info').html('Saving').show();
                    },
                    success:  function (response) {
                        if(response.code === 200){
                            $(message).removeClass("text-info").addClass('text-success').html(response.message).show().delay(3000).fadeOut();
                            if($('#refresh_on_change').is(":checked")){
                                table.ajax.reload();
                            }
                        }else{
                            $(message).removeClass("text-info").addClass('text-danger').html(response.message).show().delay(3000).fadeOut();
                        }
                    },
                    error: function (response) {
                        $(message).removeClass("text-info").addClass('text-danger').html(response.responseJSON.message).show().delay(3000).fadeOut();
                    }
                });
            }

        });
    </script>
@endsection

@section('content')

    <div id="create_discount_modal" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>New Discount Code</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-private_category-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('backend.discount.store') }}" method="POST">
                                        <input type="hidden" name="quantity" value="1">
                                        @csrf
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Code*</label>
                                                        <input id="code" name="code" value="{{ old('code') }}" type="text" placeholder="E.g. '10OFF', 'AAA123' or 'PROMOCODE10'" class="form-control input-md" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Discount Rate *</label>
                                                        <div class="input-group mb-3">
                                                            <input id="rate" name="rate" value="{{ old('rate') }}" type="number" step="0.01" min="0" placeholder="E.g. '10', '20' or '35'" class="form-control input-md" required="">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"> OFF</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label class="control-label">Quantity*</label>--}}
{{--                                                        <input id="quantity" name="quantity" value="{{ old('quantity') }}" type="number" step="1" min="0" placeholder="E.g. '1', '2' or '100'" class="form-control input-md" required="">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label class="control-label">One time use*</label>--}}
{{--                                                        <select class="form-control" id="consumable" name="consumable">--}}
{{--                                                            <option value="1">Yes</option>--}}
{{--                                                            <option value="0">No</option>--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Discount Type*</label>
                                                        <select class="form-control" id="is_percentage" name="is_percentage">
                                                            <option value="0" selected>Fixed money amount</option>
                                                            <option value="1">Percentage</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">One Time Use*</label>
                                                        <select class="form-control" id="consumable" name="consumable">
                                                            <option value="0" selected>No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Enabled*</label>
                                                        <select class="form-control" id="enabled" name="enabled">
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
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

    <form action="{{ route('backend.discount.list') }}" method="GET" id="status_form" class="d-none  float-left">
        <div class="d-flex flex-row align-items-center">
{{--            <select name="status" class="table-top-select d-flex" id="status_select">--}}
{{--                <option value="" selected="">Every Status</option>--}}
{{--                @foreach($allowed_status as $allowedStatusItem)--}}
{{--                    <option value="{{ $allowedStatusItem }}" @if((old('status')  === $allowedStatusItem) || (isset($status) && $status === $allowedStatusItem)) selected @endif>{{ ucfirst(str_replace('_', ' ', $allowedStatusItem)) }}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
            <div class="checkbox d-flex flex-row align-items-center">
                <input id="refresh_on_change" type="checkbox" value="" class="d-flex mr-2"><label for="refresh_on_change" class="mb-0"><small>Refresh on change</small></label>
            </div>
        </div>
    </form>
    <div class="wrapper-breadcrumb">
        <div class="">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        @include('backend.merchant.sidebar')
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="product-top-dt mb-3">
                            <div class="product-left-title mt-3 mt-sm-0 w-100">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h1>Discounts</h1>
                                    <a href="#" class="add-address hover-btn " data-toggle="modal" data-target="#create_discount_modal" data-selected="private_category-new">Add New Code</a>
                                </div>
                            </div>
                        </div>
                        <div class="product-list-view infinite-scroll">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="list-datatable" class="table table-striped"></table>
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

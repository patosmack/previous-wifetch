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
                    {targets: 0, orderable: true},
                    {targets: 1, orderable: true},
                    {targets: 2, orderable: false},
                    {targets: 3, orderable: true},
                    {targets: 4, orderable: true},
                    {targets: 5, orderable: true},
                    {targets: 6, orderable: true},
                    {targets: 7, orderable: true},
                    {targets: 8, orderable: true},
                    {targets: 9, orderable: true},
                    {targets: 10, orderable: false},
                    {targets: 11, orderable: false},
                ],
                columns : [
                    {
                        title: "ID", data: "id",
                        render: function ( data, type, row ) {
                            return '<span class="table-text small">'+data.value+'</span>';
                        },
                    },
                    // {
                    //     title: "Logo", data: "image",
                    //     render: function ( data, type, row ) {
                    //         return '<div class="text-center"><img src="'+data+'" class="img-fluid" width="25" /></div>';
                    //     },
                    // },
                    {
                        title: "Name", data: "name",
                        render: function ( data, type, row ) {
                            return '<span class="table-text small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "#Prods.", data: "product_count",
                        render: function ( data, type, row ) {
                            return '<span class="table-text small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Cont. Name", data: "contact_name",
                        render: function ( data, type, row ) {
                            return '<span class="table-text small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Cont. Phone", data: "contact_phone",
                        render: function ( data, type, row ) {
                            return '<span class="table-text small">'+data.value+'</span>';
                        }
                    },

                    {
                        title: "Cont. Email", data: "contact_email",
                        render: function ( data, type, row ) {
                            return '<span class="table-text small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Country", data: "country",
                        render: function ( data, type, row ) {
                            return '<span class="table-text small">'+data.value+'</span>';
                        }
                        // render: function ( data, type, row ) {
                        //     return '<div><input type="number" step="0.01" min="0.01" value="'+data.value+'" data-ref="'+data.id+'" data-message="#discount_message'+data.id+'" data-target="discount" class="table-input dynamicEditor"><p class="text-center"><small id="discount_message'+data.id+'" class="d-none"></small></p></div>';
                        // }
                    },

                    // {
                    //     title: "Parish", data: "parish",
                    //     render: function ( data, type, row ) {
                    //         return '<span class="table-text small">'+data.value+'</span>';
                    //     }
                    //     // render: function ( data, type, row ) {
                    //     //     return '<div><input type="number" step="0.01" min="0.01" value="'+data.value+'" data-ref="'+data.id+'" data-message="#discount_message'+data.id+'" data-target="discount" class="table-input dynamicEditor"><p class="text-center"><small id="discount_message'+data.id+'" class="d-none"></small></p></div>';
                    //     // }
                    // },
                    {
                        title: "Featured", data: "featured",
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="featured" data-ref="'+data.id+'" data-target="featured" data-message="#featured_message'+data.id+'" class="table-select small dynamicEditor" >'
                            if(data.value){
                                select += '<option value="0">No</option>';
                                select += '<option value="1" selected>Yes</option>';
                            }else{
                                select += '<option value="0 selected">No</option>';
                                select += '<option value="1">Yes</option>';
                            }
                            select += '</select><small id="featured_message'+data.id+'" class="d-none"></small></div>';
                            return select;
                        }
                    },
                    {
                        title: "Enabled", data: "enabled",
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="enabled" data-ref="'+data.id+'" data-target="enabled" data-message="#enabled_message'+data.id+'" class="table-select small dynamicEditor" >'
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
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="status" data-ref="'+data.id+'" data-target="status" data-message="#status_message'+data.id+'" class="table-select small dynamicEditor" >'
                            obj.settings.json.allowed_status.forEach(element => {
                                if(data.value === element){
                                    select += '<option value="'+element+'" selected>'+element+'</option>';
                                }else{
                                    select += '<option value="'+element+'">'+element+'</option>';
                                }
                            });
                            select += '</select><small id="status_message'+data.id+'" class="d-none"></small></div>';
                            return select;
                        }
                        // render: function ( data, type, row ,obj) {
                        //     let select = '<div><select name="enabled" data-ref="'+data.id+'" data-target="enabled" data-message="#status_message'+data.id+'" class="table-select dynamicEditor" >'
                        //     if(data.value){
                        //         select += '<option value="0">No</option>';
                        //         select += '<option value="1" selected>Yes</option>';
                        //     }else{
                        //         select += '<option value="0 selected">No</option>';
                        //         select += '<option value="1">Yes</option>';
                        //     }
                        //     select += '</select><small id="status_message'+data.id+'" class="d-none"></small></div>';
                        //     return select;
                        // }
                    },
                    {
                        title: "", data: "edit_action",
                        render: function ( data, type, row ) {
                            return '<div class="text-right"><a href="'+data+'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="left" title="Edit or View Information"> ' +
                                '<i class="fa fa-edit"></i>' +
                                '</a></div>';
                        }
                    },
                    {
                        title: "", data: "item_action",
                        render: function ( data, type, row ) {
                            return '<div class="text-right"><a href="'+data+'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="left" title="Edit or View Products">' +
                                '<i class="fa fa-tags"></i>' +
                                '</a></div>';
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
                    url: "{{ route('backend.merchant.json', (isset($category) && $category) ? $category : null) }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType: "json",
                    error: function (request, error) {
                        console.log(arguments);
                        alert(" Can't do because: " + error);
                    },
                }

            });

            generateCategoryFilterToolbar();

            function generateCategoryFilterToolbar(){
                let catObj = $('#category_form').clone().removeClass('d-none');
                $("#list-datatable_filter").append(catObj);
                $('#category_form').remove();
            }

            $(document).on( "change", "#category_select", function(e){
                $('#category_form').submit();
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
                    url:   '{{ route('backend.merchant.easy-edit') }}',
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

    <form action="{{ route('backend.merchant.list') }}" method="GET" id="category_form" class="d-none  float-left">
        <div class="d-flex flex-row align-items-center">
            <a href="{{ route('backend.merchants.export') }}" class="mr-2 btn btn-sm btn-primary">Export</a>
            <select name="category" class="table-top-select d-flex" id="category_select">
                <option value="" selected="">All categories</option>
                <option value="-1" @if(isset($category) && (int)$category === (int)-1) selected @endif>No Category</option>
                @foreach($categories as $categoryItem)
                    <option value="{{ $categoryItem->id }}" @if((old('category')  === (int)$categoryItem->id) || (isset($category) && (int)$category === (int)$categoryItem->id)) selected @endif>{{ $categoryItem->name }}</option>
                @endforeach
            </select>
            <div class="checkbox d-flex flex-row align-items-center ml-5">
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
                            <div class="product-left-title mt-3 mt-sm-0">
                                <h1>Merchants @if(isset($total_products))<small>{{ $total_products }} products</small>@endif</h1>
                            </div>
{{--                            @if($merchant->cover_image)--}}
{{--                                <img src="{{ asset($merchant->cover_image) }}" alt="" class="cover-image img-fluid mt-3 mb-3">--}}
{{--                            @endif--}}
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

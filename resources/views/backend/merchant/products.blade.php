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
                    {targets: 0, orderable: false,},
                    {targets: 1, orderable: false},
                    {targets: 2, orderable: true},
                    {targets: 3, orderable: true},
                    {targets: 4, orderable: false},
                    {targets: 5, orderable: true},
                    {targets: 6, orderable: true},
                    {targets: 7, orderable: true},
                    {targets: 8, orderable: false}
                ],
                columns : [
                    {
                        title: "Image", data: "image",
                        render: function ( data, type, row ) {
                            return '<div class="text-center"><img src="'+data+'" class="img-fluid" width="25" /></div>';
                        },
                    },
                    {
                        title: "Category", data: "private_category",
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="private_category_id" data-ref="'+data.id+'" data-target="category" data-message="#category_message'+data.id+'" class="table-select dynamicEditor" >'
                            select += '<option value="" selected>No category</option>';
                            obj.settings.json.categories.forEach(element => {
                                if(data.value === element.id){
                                    select += '<option value="'+element.id+'" selected>'+element.name+'</option>';
                                }else{
                                    select += '<option value="'+element.id+'">'+element.name+'</option>';
                                }
                            });
                            select += '</select><small id="category_message'+data.id+'" class="d-none"></small></div>';
                            return select;
                        }
                    },
                    {
                        title: "Name", data: "name",
                        render: function ( data, type, row ) {
                            return '<span class="table-text">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Price", data: "price",
                        render: function ( data, type, row ) {
                            return '<div><input type="number" step="0.01" min="0.01" value="'+data.value+'" data-ref="'+data.id+'" data-message="#price_message'+data.id+'" data-target="price" class="table-input dynamicEditor"><p class="text-center"><small id="price_message'+data.id+'" class="d-none"></small></p></div>';
                        }
                    },
                    {
                        title: "Sell Price", data: "sell_price",
                        render: function ( data, type, row ) {
                            return '<span class="table-text">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Discount", data: "discount",
                        render: function ( data, type, row ) {
                            return '<div><input type="number" step="0.01" min="0.01" value="'+data.value+'" data-ref="'+data.id+'" data-message="#discount_message'+data.id+'" data-target="discount" class="table-input dynamicEditor"><p class="text-center"><small id="discount_message'+data.id+'" class="d-none"></small></p></div>';
                        }
                    },
                    {
                        title: "Featured", data: "featured",
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="featured" data-ref="'+data.id+'" data-target="featured" data-message="#featured_message'+data.id+'" class="table-select dynamicEditor" >'
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
                        title: "Status", data: "enabled",
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="enabled" data-ref="'+data.id+'" data-target="enabled" data-message="#status_message'+data.id+'" class="table-select dynamicEditor" >'
                                if(data.value){
                                    select += '<option value="0">Disabled</option>';
                                    select += '<option value="1" selected>Enabled</option>';
                                }else{
                                    select += '<option value="0 selected">Disabled</option>';
                                    select += '<option value="1">Enabled</option>';
                                }
                            select += '</select><small id="status_message'+data.id+'" class="d-none"></small></div>';
                            return select;
                        }
                    },
                    {
                        title: "Actions", data: "actions",
                        render: function ( data, type, row ) {
                            return '<div class="text-right"><a href="'+data+'" class="btn btn-sm btn-info">' +
                                'Edit' +
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
                    url: "{{ route('backend.merchant.products.json', ['id' => $merchant->id, 'private_category_id' => $private_category]) }}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType: "json",
                }

            });

            generateCategoryFilterToolbar();

            function generateCategoryFilterToolbar(){
                let catObj = $('#private_category_form').clone().removeClass('d-none');
                $("#list-datatable_filter").append(catObj);
                $('#private_category_form').remove();
            }

            $(document).on( "change", "#private_category_select", function(e){
                $('#private_category_form').submit();
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
                    url:   '{{ route('backend.merchant.products.easy-edit') }}',
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

    <form action="{{ route('backend.merchant.products', $merchant->id) }}" method="GET" id="private_category_form" class="d-none  float-left">
        <div class="d-flex flex-row align-items-center">
            <select name="private_category" class="table-top-select d-flex" id="private_category_select">
                <option value="" selected="">All categories</option>
                <option value="-1" @if(isset($private_category) && (int)$private_category === (int)-1) selected @endif>No Category</option>
                @foreach($merchant->privateCategories as $privateCategoryItem)
                    <option value="{{ $privateCategoryItem->id }}" @if((old('private_category')  === (int)$privateCategoryItem->id) || (isset($private_category) && (int)$private_category === (int)$privateCategoryItem->id)) selected @endif>{{ $privateCategoryItem->name }}</option>
                @endforeach
            </select>
            <div class="checkbox d-flex flex-row align-items-center ml-5">
                <input id="refresh_on_change" type="checkbox" value="" class="d-flex mr-2"><label for="refresh_on_change" class="mb-0"><small>Refresh on change</small></label>
            </div>
        </div>
    </form>


    <div class="wrapper">
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
                                    <a href="{{ route('backend.merchant.list') }}"><h4><i class="uil uil-arrow-circle-left"></i>Back to merchants</h4></a>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 mt-4">

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
        </div>
    </div>
@endsection

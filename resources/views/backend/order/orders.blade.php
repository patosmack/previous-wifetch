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
                    {targets: 5, orderable: true},
                    {targets: 6, orderable: true},
                    {targets: 7, orderable: true},
                    {targets: 8, orderable: true},
                    {targets: 9, orderable: true},
                    {targets: 10, orderable: true},
                    {targets: 11, orderable: false},
                    {targets: 12, orderable: false},

                ],
                columns : [
                    {
                        title: "ID", data: "id",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Created at", data: "created_at",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Name", data: "order_name",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Email", data: "order_email",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small" style="font-size: 10px;white-space: nowrap">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Phone", data: "delivery_phone",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Merchant", data: "merchant_name",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },

                    {
                        title: "Country", data: "country",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Parish", data: "parish",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Status", data: "status",
                        render: function ( data, type, row ,obj) {
                            let select = '<div><select name="status" data-ref="'+data.id+'" data-target="status" data-message="#status_message'+data.id+'" class="table-select small dynamicEditor" >'
                            obj.settings.json.allowed_status.forEach(element => {
                                let name = element.split('_').join(' ');
                                if(data.value === element){
                                    select += '<option value="'+element+'" selected>'+  name[0].toUpperCase() + name.slice(1) +'</option>';
                                }else{
                                    select += '<option value="'+element+'">'+ name[0].toUpperCase() + name.slice(1)  +'</option>';
                                }
                            });
                            select += '</select><small id="status_message'+data.id+'" class="d-none"></small></div>';
                            return select;
                        }
                    },
                    {
                        title: "Payment", data: "transaction_status",
                        render: function ( data, type, row ) {
                            if(data.value !== 'Pending'){
                                return '<span class="table-text font-weight-bold x-small">'+data.value+'</span>';
                            }
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Delivery Date", data: "delivery_date",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Time", data: "delivery_timeframe",
                        render: function ( data, type, row ) {
                            let timeframe = data.value.split('-').join('<br/>');
                            return '<span class="table-text x-small text-center d-block">'+ timeframe +'</span>';
                        }
                    },
                    {
                        title: "Rating", data: "rating",
                        render: function ( data, type, row ) {
                            return '<span class="table-text x-small">'+data.value+'</span>';
                        }
                    },
                    {
                        title: "Actions", data: "actions",
                        render: function ( data, type, row ) {
                            return '<div class="text-right"><a href="'+data+'" class="btn btn-sm btn-info">' +
                                'View' +
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
                    url: "{{ route('backend.order.json', (isset($status) && $status) ? $status : null) }}",
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
                    url:   '{{ route('backend.order.easy-edit') }}',
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

    <form action="{{ route('backend.order.list') }}" method="GET" id="status_form" class="d-none  float-left">
        <div class="d-flex flex-row align-items-center">
            <a href="{{ route('backend.orders.export') }}" class="mr-2 btn btn-sm btn-primary">Export</a>
            <select name="status" class="table-top-select d-flex" id="status_select">
                <option value="" selected="">Every Status</option>
                @foreach($allowed_status as $allowedStatusItem)
                    <option value="{{ $allowedStatusItem }}" @if((old('status')  === $allowedStatusItem) || (isset($status) && $status === $allowedStatusItem)) selected @endif>{{ ucfirst(str_replace('_', ' ', $allowedStatusItem)) }}</option>
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
                                <h1>Orders</h1>
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

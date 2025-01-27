
@if(Session::get('success') || Session::get('error') || Session::get('warning') || Session::get('info') || $errors->any())
    <div class="container" id="alert_container">
        <div class="row pt-5 pb-5">
            <div class="col-md-8 offset-md-2">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible mb-0">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible mb-0">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif


                @if ($message = Session::get('warning'))
                    <div class="alert alert-warning alert-dismissible mb-0">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif


                @if ($message = Session::get('info'))
                    <div class="alert alert-info alert-dismissible mb-0">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif


                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible mb-0">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{--            <div class="alert alert-warning alert-dismissible mb-0" role="alert">--}}
                {{--                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>--}}
                {{--                <strong>Warning!</strong> Better check yourself, you're not looking too good.--}}
                {{--            </div>--}}
            </div>
        </div>
    </div>
@endif

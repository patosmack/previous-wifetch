@extends('frontend.app')

@section('styles')

@endsection

@section('scripts')


@endsection

@section('content')

    <div class="wrapper">
        @include('frontend.shared.alert')
        <div class="default-dt">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="title129">
                            <h2 class="m-0 p-0">About Us</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="life-theme">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="default-title left-text">
                            <h1>We do it for you.</h1>
                            <p>Customer focused. Solution driven. Results based.</p>
                        </div>
                        <div class="about-content">
                            <p>WiFetch is transforming the way goods move around in the Caribbean locally, enabling anyone to have anything delivered on-demand. Our revolutionary local Logistics platform connects customers with local Fetchers who can deliver anything from any of our partner vendors within hours. We empower communities to shop local and remotely and empower businesses through our API to offer delivery at the most economical/most efficient cost.</p>
                        </div>
                        <div class="default-title left-text mt-2">
                            <h3 class="pt-2">What Can WiFetch For You?</h3>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="about-img">
                            <img src="{{ asset('assets/common/about.svg') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="how-order-theme">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="default-title">
                            <h2>How Do I Order?</h2>
                            <p>Whatever you need, WiFetch & WiDeliver</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="how-order-steps">
                            <div class="how-order-icon">
                                <i class="uil uil-search"></i>
                            </div>
                            <h4>Browse wifetch.com for products or use the search feature</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="how-order-steps">
                            <div class="how-order-icon">
                                <i class="uil uil-shopping-basket"></i>
                            </div>
                            <h4>Add item to your shopping cart</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="how-order-steps">
                            <div class="how-order-icon">
                                <i class="uil uil-stopwatch"></i>
                            </div>
                            <h4>Choose a convenient delivery time</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="how-order-steps">
                            <div class="how-order-icon">
                                <i class="uil uil-money-bill"></i>
                            </div>
                            <h4>Select suitable payment option</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="how-order-steps">
                            <div class="how-order-icon">
                                <i class="uil uil-truck"></i>
                            </div>
                            <h4>Your products will be delivered as per your order.</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="how-order-steps">
                            <div class="how-order-icon">
                                <i class="uil uil-smile"></i>
                            </div>
                            <h4>Happy Curstomers</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <hr>
                </div>
                <div class="col-md-6">
                    <div class="job-des-dt142 policy-des-dt text-center">
                        <h1 class="text-center pt-2 pt-md-4 pb-2 pb-md-4">Our Mission</h1>
                        <p class="text-center pb-2 pb-md-4">
                            Our mission is to empower the caribbean and provide opportunities and access for everyone We want everyone's business to be noticeable and successful. We want to help you feel connected in a sometimes disconnected world. We want to help YOU grow.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="job-des-dt142 policy-des-dt text-center">
                        <h1 class="text-center pt-2 pt-md-4 pb-2 pb-md-4">Our Values</h1>
                        <p class="text-center pb-2 pb-md-4">
                            At WiFetch our values are extremely important to us and are at the core of our business. We believe in providing solutions for all of our clients, with the client being the epicentre of our business model.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="life-theme">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <hr>
                    </div>
                    <div class="col-lg-12">
                        <div class="default-title">
                            <h2>Meet The Team</h2>
                        </div>
                    </div>
                    <div class="col-md-8 offset-md-2 mt-5">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="item">
                                    <div class="team-item">
                                        <div class="team-img">
                                            <img src="{{ asset('assets/about/lilly.jpg') }}" alt="Lily Dash">
                                        </div>
                                        <h4>Lily Dash</h4>
                                        <span>Co-Founder</span>
                                        {{--                                        <ul class="team-social">--}}
                                        {{--                                            <li><a href="#" class="scl-btn hover-btn"><i class="fab fa-facebook-f"></i></a></li>--}}
                                        {{--                                            <li><a href="#" class="scl-btn hover-btn"><i class="fab fa-linkedin-in"></i></a></li>--}}
                                        {{--                                        </ul>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="item">
                                    <div class="team-item">
                                        <div class="team-img">
                                            <img src="{{ asset('assets/about/sophie.jpg') }}" alt="Sophie Bannister">
                                        </div>
                                        <h4>Sophie Bannister</h4>
                                        <span>Co-Founder</span>
                                        {{--                                        <ul class="team-social">--}}
                                        {{--                                            <li><a href="#" class="scl-btn hover-btn"><i class="fab fa-facebook-f"></i></a></li>--}}
                                        {{--                                            <li><a href="#" class="scl-btn hover-btn"><i class="fab fa-linkedin-in"></i></a></li>--}}
                                        {{--                                        </ul>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <hr>
                </div>
                <div class="col-lg-12">
                    <div class="job-des-dt142 policy-des-dt text-center">
                        <h1 class="text-center pt-2 pt-md-4 pb-2 pb-md-4">WiFetch is Powered by UNDP</h1>
                        <p class="text-center pb-2 pb-4">UNDP and WiFETCH will connect businesses - who have lost customers - to buyers; it will also continue ensuring safe deliveries at home, assisting Barbadians who face special difficulties in procuring for their daily needs, by integrating hotline and volunteer services.</p>

                        <a href="https://www.bb.undp.org/content/barbados/en/home/presscenter/pressreleases/20191/digital-economy-provides-crisis-jobs-and-safe-services/" class="main-btn-border" title="Digital Economy Provides Crisis Jobs and Safe Services">
                            View Announcement
                        </a>
                    </div>
                </div>
            </div>
        </div>




    </div>
@endsection

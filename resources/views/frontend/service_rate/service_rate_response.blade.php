@extends('frontend.app')

@section('styles')

    <style>




        .emoji {
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: .3s;
        }

        .emoji > svg {
            margin: 15px 0;
            width: 70px;
            height: 70px;
            flex-shrink: 0;
        }



    </style>
@endsection

@section('scripts')

@endsection

@section('content')

    <div class="wrapper">
        @include('frontend.shared.alert')
        <div class="life-theme">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="default-title left-text">
                            <h1 class="text-center">We really appreciate you taking the time to share your rating with us. We look forward to seeing you again soon</h1>
                        </div>
                        <div class="about-content">


                            <div class="emoji-wrapper">
                                <div class="emoji">

                                    <svg class="rating-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                        <path d="M407.7 352.8a163.9 163.9 0 0 1-303.5 0c-2.3-5.5 1.5-12 7.5-13.2a780.8 780.8 0 0 1 288.4 0c6 1.2 9.9 7.7 7.6 13.2z" fill="#3e4347"/>
                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534"/>
                                        <g fill="#fff">
                                            <path d="M115.3 339c18.2 29.6 75.1 32.8 143.1 32.8 67.1 0 124.2-3.2 143.2-31.6l-1.5-.6a780.6 780.6 0 0 0-284.8-.6z"/>
                                            <ellipse cx="356.4" cy="205.3" rx="81.1" ry="81"/>
                                        </g>
                                        <ellipse cx="356.4" cy="205.3" rx="44.2" ry="44.2" fill="#3e4347"/>
                                        <g fill="#fff">
                                            <ellipse transform="scale(-1) rotate(45 454 -906)" cx="375.3" cy="188.1" rx="12" ry="8.1"/>
                                            <ellipse cx="155.6" cy="205.3" rx="81.1" ry="81"/>
                                        </g>
                                        <ellipse cx="155.6" cy="205.3" rx="44.2" ry="44.2" fill="#3e4347"/>
                                        <ellipse transform="scale(-1) rotate(45 454 -421.3)" cx="174.5" cy="188" rx="12" ry="8.1" fill="#fff"/>
                                    </svg>
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

@extends('frontend.layouts.master')

@section('content')


    <main>

        <section class="breadcrumb">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="breadcrumb__wrapper">
                            <h2 class="breadcrumb__title"> Properties</h2>
                            <ul class="breadcrumb__list">
                                <li class="breadcrumb__item">
                                    <a href="index.html"> Home</a>
                                </li>
                                <li class="breadcrumb__item">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </li>
                                <li class="breadcrumb__item">
                                    <span class="breadcrumb__item-text"> Properties</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="blog-section section-one-bg py-120">
            <div class="container">
                <div class="row row-gap-5 justify-content-center">

                    <!-- Property Item -->
                    <div class="col-lg-3 col-md-6">
                        <div class="blog-grid-item">
                            <div class="blog-date">
                                <div class="bar-icon"></div>
                                ISS: 65
                            </div>

                            <a href="#">
                                <figure class="image-effect">
                                    <img src="{{ asset('frontend/assets/images/properties/1.jpg') }}" alt="Property" class="img-fluid w-100">
                                </figure>
                            </a>

                            <div class="post-type">
                                ₹4.50Cr
                                <div class="bar-icon2"></div>
                            </div>

                            <div class="blog-content">
                                <h4><a href="#">Luxury 3BHK Apartment</a></h4>

                                <p class="mt-2 mb-1">
                                    <i class="fa fa-map-marker"></i> Mumbai, Maharashtra
                                </p>

                                <ul class="property-details-table mt-2 mb-0">
                                    <li>
                                        <span class="label">Area</span>
                                        <span class="value">1800 sq ft</span>
                                    </li>
                                    <li>
                                        <span class="label">Beds</span>
                                        <span class="value">3</span>
                                    </li>
                                    <li>
                                        <span class="label">Yield</span>
                                        <span class="value">4.5%</span>
                                    </li>
                                    <li>
                                        <span class="label">Growth</span>
                                        <span class="value">25%</span>
                                    </li>
                                </ul>


                                <a href="properties_details.html" class="btn btn-primary w-100 mt-3">View Details</a>
                            </div>
                        </div>
                    </div>

                    <!-- Property Item -->
                    <div class="col-lg-3 col-md-6">
                        <div class="blog-grid-item">
                            <div class="blog-date">
                                <div class="bar-icon"></div>
                                ISS: 65
                            </div>

                            <a href="#">
                                <figure class="image-effect">
                                    <img src="{{ asset('frontend/assets/images/properties/1.jpg') }}" alt="Property" class="img-fluid w-100">
                                </figure>
                            </a>

                            <div class="post-type">
                                ₹4.50Cr
                                <div class="bar-icon2"></div>
                            </div>

                            <div class="blog-content">
                                <h4><a href="#">Luxury 3BHK Apartment</a></h4>

                                <p class="mt-2 mb-1">
                                    <i class="fa fa-map-marker"></i> Mumbai, Maharashtra
                                </p>

                                <ul class="property-details-table mt-2 mb-0">
                                    <li>
                                        <span class="label">Area</span>
                                        <span class="value">1800 sq ft</span>
                                    </li>
                                    <li>
                                        <span class="label">Beds</span>
                                        <span class="value">3</span>
                                    </li>
                                    <li>
                                        <span class="label">Yield</span>
                                        <span class="value">4.5%</span>
                                    </li>
                                    <li>
                                        <span class="label">Growth</span>
                                        <span class="value">25%</span>
                                    </li>
                                </ul>


                                <a href="properties_details.html" class="btn btn-primary w-100 mt-3">View Details</a>
                            </div>
                        </div>
                    </div>

                    <!-- Property Item -->
                    <div class="col-lg-3 col-md-6">
                        <div class="blog-grid-item">
                            <div class="blog-date">
                                <div class="bar-icon"></div>
                                ISS: 65
                            </div>

                            <a href="#">
                                <figure class="image-effect">
                                    <img src="{{ asset('frontend/assets/images/properties/1.jpg') }}" alt="Property" class="img-fluid w-100">
                                </figure>
                            </a>

                            <div class="post-type">
                                ₹4.50Cr
                                <div class="bar-icon2"></div>
                            </div>

                            <div class="blog-content">
                                <h4><a href="#">Luxury 3BHK Apartment</a></h4>

                                <p class="mt-2 mb-1">
                                    <i class="fa fa-map-marker"></i> Mumbai, Maharashtra
                                </p>

                                <ul class="property-details-table mt-2 mb-0">
                                    <li>
                                        <span class="label">Area</span>
                                        <span class="value">1800 sq ft</span>
                                    </li>
                                    <li>
                                        <span class="label">Beds</span>
                                        <span class="value">3</span>
                                    </li>
                                    <li>
                                        <span class="label">Yield</span>
                                        <span class="value">4.5%</span>
                                    </li>
                                    <li>
                                        <span class="label">Growth</span>
                                        <span class="value">25%</span>
                                    </li>
                                </ul>


                                <a href="properties_details.html" class="btn btn-primary w-100 mt-3">View Details</a>
                            </div>
                        </div>
                    </div>



                    <!-- Pagination -->
                    <div class="col-12">
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item"><a class="page-link active" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        →
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </section>




    </main>

    @endsection
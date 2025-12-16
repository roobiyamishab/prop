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


        <section class="section-one-bg py-120">
            <div class="container">
                <div class="row row-gap-5 justify-content-center">
                    <div class="col-lg-8">
                        <div class="d-grid row-gap-5">
                            <div class="blog-details-content">

                               

                                <figure class="image-effect blog-dt-img1">
                                    <img src="{{ asset('frontend/assets/images/properties/1.jpg') }}" alt="properties images" class="img-fluid w-100">
                                </figure>

                                 <h4>Luxury 3BHK Apartment</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

                                <p class="mt-2 mb-3">
                                    <i class="fa fa-map-marker"></i> Mumbai, Maharashtra
                                </p>

                                <!-- Property Details -->
                                <div class="property-details">

                                    <div class="row mb-2">
                                        <div class="col-6 fw-bold">Area</div>
                                        <div class="col-6 text-end">1800 sq ft</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-6 fw-bold">Beds</div>
                                        <div class="col-6 text-end">3</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-6 fw-bold">Yield</div>
                                        <div class="col-6 text-end">4.5%</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-6 fw-bold">Growth</div>
                                        <div class="col-6 text-end">25%</div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="row row-gap-4">
                            <div class="col-12">
                                <div class="sidebar-item">
                                    <h5>Search Here</h5>
                                    <form action="#">
                                        <input type="text" name="search" placeholder="Search...">
                                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="sidebar-item">
                                    <h5>Properties</h5>
                                    <ul class="category-file">

                                        <li>
                                            <a href="#">
                                                <span class="category-title">
                                                    <i class="fa fa-angle-double-right"></i>
                                                    Luxury 3BHK Apartment
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="category-title">
                                                    <i class="fa fa-angle-double-right"></i>
                                                    Luxury 3BHK Apartment
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="category-title">
                                                    <i class="fa fa-angle-double-right"></i>
                                                    Luxury 3BHK Apartment
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="category-title">
                                                    <i class="fa fa-angle-double-right"></i>
                                                    Luxury 3BHK Apartment
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="category-title">
                                                    <i class="fa fa-angle-double-right"></i>
                                                    Luxury 3BHK Apartment
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="category-title">
                                                    <i class="fa fa-angle-double-right"></i>
                                                    Luxury 3BHK Apartment
                                                </span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>




    </main>

     @endsection
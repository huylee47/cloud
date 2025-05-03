@extends('client.layouts.master')

@section('main')
    <div id="content" class="site-content">
        <div class="col-full">
            <div class="row">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main">
                        <div class="home-v1-slider home-slider">
                            @foreach ($loadBanner as $banner)
                                <div class="slider-1"
                                    style=" height: 400px;background-size: 90% ;background-image:  url({{ asset('admin/assets/images/banner/' . $banner->image) }}); ">

                                    {{-- <div class="caption">
                                        <div class="button" style="margin-top: 300px">Mua sắm ngay
                                            <i class="tm tm-long-arrow-right"></i>
                                        </div>

                                    </div> --}}
                                </div>
                            @endforeach
                            <!-- .slider-2 -->
                        </div>
                        <!-- .home-v1-slider -->
                        <div class="features-list">
                            <div class="features">
                                <div class="feature">
                                    <div class="media">
                                        <i class="feature-icon d-flex mr-3 tm tm-free-delivery"></i>
                                        <div class="media-body feature-text">
                                            <h5 class="mt-0">Vận chuyển ưu đãi</h5>
                                            <span>tối đa 300k</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- .feature -->
                                <div class="feature">
                                    <div class="media">
                                        <i class="feature-icon d-flex mr-3 tm tm-feedback"></i>
                                        <div class="media-body feature-text">
                                            <h5 class="mt-0">90% Khách hàng</h5>
                                            <span>Hài lòng</span>
                                        </div>
                                    </div>
                                    <!-- .media -->
                                </div>
                                <!-- .feature -->
                                <div class="feature">
                                    <div class="media">
                                        <i class="feature-icon d-flex mr-3 tm tm-free-return"></i>
                                        <div class="media-body feature-text">
                                            <h5 class="mt-0">3 ngày</h5>
                                            <span>Đổi trả hàng miễn phí</span>
                                        </div>
                                    </div>
                                    <!-- .media -->
                                </div>
                                <!-- .feature -->
                                <div class="feature">
                                    <div class="media">
                                        <i class="feature-icon d-flex mr-3 tm tm-safe-payments"></i>
                                        <div class="media-body feature-text">
                                            <h5 class="mt-0">Hệ thống thanh toán</h5>
                                            <span>An toàn , bảo mật</span>
                                        </div>
                                    </div>
                                    <!-- .media -->
                                </div>
                                <!-- .feature -->
                                <div class="feature">
                                    <div class="media">
                                        <i class="feature-icon d-flex mr-3 tm tm-best-brands"></i>
                                        <div class="media-body feature-text">
                                            <h5 class="mt-0">Sản phẩm</h5>
                                            <span>Giá tốt nhất</span>
                                        </div>
                                    </div>
                                    <!-- .media -->
                                </div>
                                <!-- .feature -->
                            </div>
                            <!-- .features -->
                        </div>
                        <!-- /.features list -->
                        <div class="section-deals-carousel-and-products-carousel-tabs row">
                            <section class="column-1 deals-carousel" id="sale-with-timer-carousel">
                                <div class="deals-carousel-inner-block">
                                    <header class="section-header">
                                        <h2 class="section-title">
                                            <strong>Sản phẩm</strong> giảm giá
                                        </h2>
                                        <nav class="custom-slick-nav"></nav>
                                    </header>
                                    <!-- /.section-header -->
                                    <div class="sale-products-with-timer-carousel deals-carousel-v1">
                                        <div class="products-carousel">
                                            <div class="container-fluid">
                                                <div class="woocommerce columns-1">
                                                    <div class="products" data-ride="tm-slick-carousel"
                                                        data-wrap=".products"
                                                        data-slick="{&quot;infinite&quot;:false,&quot;slidesToShow&quot;:1,&quot;slidesToScroll&quot;:1,&quot;dots&quot;:false,&quot;arrows&quot;:true,&quot;prevArrow&quot;:&quot;&lt;a href=\&quot;#\&quot;&gt;&lt;i class=\&quot;tm tm-arrow-left\&quot;&gt;&lt;\/i&gt;&lt;\/a&gt;&quot;,&quot;nextArrow&quot;:&quot;&lt;a href=\&quot;#\&quot;&gt;&lt;i class=\&quot;tm tm-arrow-right\&quot;&gt;&lt;\/i&gt;&lt;\/a&gt;&quot;,&quot;appendArrows&quot;:&quot;#sale-with-timer-carousel .custom-slick-nav&quot;,&quot;responsive&quot;:[{&quot;breakpoint&quot;:767,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesToScroll&quot;:1}},{&quot;breakpoint&quot;:1023,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesToScroll&quot;:2}}]}">
                                                        @if ($discountedProducts->isEmpty())
                                                            <div class="alert alert-info" role="alert">
                                                                Chưa có sản phẩm nào được giảm giá.
                                                            </div>
                                                        @else
                                                            @foreach ($discountedProducts as $dp)
                                                                @php
                                                                    $timeDiff =
                                                                        strtotime($dp->promotion->end_date) - time();
                                                                    $promotion = $dp->where('id', $dp->id)->first();
                                                                    $hasDiscount = $promotion ? true : false;
                                                                    $discountedPrice = $hasDiscount
                                                                        ? $dp->base_price *
                                                                            (1 -
                                                                                $promotion->promotion
                                                                                    ->discount_percent /
                                                                                    100)
                                                                        : null;
                                                                @endphp

                                                                @if ($timeDiff > 0)
                                                                    <div class="sale-product-with-timer product">
                                                                        <a class="woocommerce-LoopProduct-link"
                                                                            href="{{ route('client.product.show', ['slug' => $dp->slug]) }}">

                                                                            <div class="sale-product-with-timer-header">
                                                                                <div class="price-and-title">
                                                                                    <span class="price">
                                                                                        @if ($dp->variant->count() > 0)
                                                                                            <ins>
                                                                                                <span
                                                                                                    class="woocommerce-Price-amount amount">
                                                                                                    <span
                                                                                                        class="woocommerce-Price-currencySymbol">Từ</span>
                                                                                                    {{ number_format($dp->variant->min('discounted_price'), 0, ',', '.') }}
                                                                                                    đ
                                                                                                </span>
                                                                                            </ins>
                                                                                            <del>
                                                                                                <span
                                                                                                    class="woocommerce-Price-amount amount">
                                                                                                    <span
                                                                                                        class="woocommerce-Price-currencySymbol">
                                                                                                        {{ number_format($dp->variant->min('price'), 0, ',', '.') }}
                                                                                                        đ
                                                                                                    </span>
                                                                                                </span>
                                                                                            </del>
                                                                                        @else
                                                                                            <ins>
                                                                                                <span
                                                                                                    class="woocommerce-Price-amount amount">
                                                                                                    <span
                                                                                                        class="woocommerce-Price-currencySymbol">Từ</span>
                                                                                                    {{ number_format($dp->discountedPrice, 0, ',', '.') }}
                                                                                                    đ
                                                                                                </span>
                                                                                            </ins>
                                                                                            <del>
                                                                                                <span
                                                                                                    class="woocommerce-Price-amount amount">
                                                                                                    <span
                                                                                                        class="woocommerce-Price-currencySymbol">
                                                                                                        {{ number_format($dp->base_price, 0, ',', '.') }}
                                                                                                        đ
                                                                                                    </span>
                                                                                                </span>
                                                                                            </del>
                                                                                        @endif
                                                                                    </span>
                                                                                    <!-- /.price -->
                                                                                    <h2
                                                                                        class="woocommerce-loop-product__title">
                                                                                        {{ $dp->name }}
                                                                                    </h2>
                                                                                </div>
                                                                                <!-- /.price-and-title -->
                                                                                <div class="sale-label-outer">
                                                                                    <div class="sale-saved-label">
                                                                                        <span
                                                                                            class="saved-label-text">Giảm</span>
                                                                                        <span class="saved-label-amount">
                                                                                            <span
                                                                                                class="woocommerce-Price-amount amount">
                                                                                                {{ $dp->promotion->discount_percent }}
                                                                                                %
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                    <!-- /.sale-saved-label -->
                                                                                </div>
                                                                                <!-- /.sale-label-outer -->
                                                                            </div>
                                                                            <!-- /.sale-product-with-timer-header -->
                                                                            <img class="img-fluid fixed-sale-image"
                                                                                src="{{ url('') }}/admin/assets/images/product/{{ $dp->img }}"
                                                                                alt="Product Image">
                                                                            <!-- /.deal-progress -->
                                                                            <div class="deal-countdown-timer">
                                                                                <div class="marketing-text">
                                                                                    <span class="line-1">Nhanh nào!</span>
                                                                                    <span class="line-2">Giảm giá kết thúc
                                                                                        trong:</span>
                                                                                </div>
                                                                                <!-- /.marketing-text -->
                                                                                <span class="deal-time-diff"
                                                                                    style="display:none;">{{ $timeDiff }}</span>
                                                                                <div class="deal-countdown countdown"></div>
                                                                            </div>
                                                                            <!-- /.deal-countdown-timer -->
                                                                        </a>
                                                                        <!-- /.woocommerce-LoopProduct-link -->
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        {{-- 2 --}}
                                                        <!-- /.sale-product-with-timer -->

                                                        <!-- /.sale-product-with-timer -->

                                                        <!-- /.sale-product-with-timer -->
                                                    </div>
                                                    <!-- /.products -->
                                                </div>
                                                <!-- /.woocommerce -->
                                            </div>
                                            <!-- /.container-fluid -->
                                        </div>
                                        <!-- /.slick-list -->
                                    </div>
                                    <!-- /.sale-products-with-timer-carousel -->
                                    @if ($discountedProducts->isEmpty())
                                        <p></p>
                                    @else
                                        <footer class="section-footer">
                                            <nav class="custom-slick-pagination d-flex justify-content-center align-items-center">
                                                <a class="slider-prev left mx-3" href="#"
                                                    data-target="#sale-with-timer-carousel .products">
                                                    <i class="tm tm-arrow-left"></i>Trước đó</a>
                                                <a class="slider-next right mx-3" href="#"
                                                    data-target="#sale-with-timer-carousel .products">Xem tiếp<i
                                                        class="tm tm-arrow-right"></i></a>
                                            </nav>
                                        </footer>
                                    @endif

                                    <!-- /.section-footer -->
                                </div>
                                <!-- /.deals-carousel-inner-block -->
                            </section>
                            <!-- /.deals-carousel -->
                            <section class="column-2 section-products-carousel-tabs tab-carousel-1">
                                <div class="section-products-carousel-tabs-wrap">
                                    <header class="section-header">
                                        <ul role="tablist" class="nav justify-content-end">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#tab-59f89f0881f930"
                                                    data-toggle="tab">Sản
                                                    phẩm mới</a>
                                            </li>
                                            {{-- <li class="nav-item">
                                                <a class="nav-link " href="#tab-59f89f0881f931" data-toggle="tab">On
                                                    Sale</a>
                                            </li> --}}
                                            {{-- <li class="nav-item">
                                                <a class="nav-link " href="#tab-59f89f0881f932" data-toggle="tab">Đánh
                                                    giá cao</a>
                                            </li> --}}
                                        </ul>
                                    </header>
                                    <!-- .section-header -->
                                    <div class="tab-content">
                                        <div id="tab-59f89f0881f930" class="tab-pane active" role="tabpanel">
                                            <div class="products-carousel" data-ride="tm-slick-carousel"
                                                data-wrap=".products"
                                                data-slick="{&quot;infinite&quot;:false,&quot;rows&quot;:2,&quot;slidesPerRow&quot;:5,&quot;slidesToShow&quot;:1,&quot;slidesToScroll&quot;:1,&quot;dots&quot;:true,&quot;arrows&quot;:false,&quot;responsive&quot;:[{&quot;breakpoint&quot;:1023,&quot;settings&quot;:{&quot;slidesPerRow&quot;:2}},{&quot;breakpoint&quot;:1169,&quot;settings&quot;:{&quot;slidesPerRow&quot;:4}},{&quot;breakpoint&quot;:1400,&quot;settings&quot;:{&quot;slidesPerRow&quot;:3}}]}">
                                                <div class="container-fluid">
                                                    <div class="woocommerce">
                                                        <div class="products">
                                                            @foreach ($newProduct as $np)
                                                                @php
                                                                    $promotion = $discountedProducts
                                                                        ->where('id', $np->id)
                                                                        ->first();
                                                                    $hasDiscount = $promotion ? true : false;
                                                                    $discountedPrice = $hasDiscount
                                                                        ? $np->base_price *
                                                                            (1 -
                                                                                $promotion->promotion
                                                                                    ->discount_percent /
                                                                                    100)
                                                                        : null;
                                                                @endphp
                                                                <div class="product">
                                                                    <a href="{{ route('client.product.show', ['slug' => $np->slug]) }}"
                                                                        class="woocommerce-LoopProduct-link">
                                                                        <img src="{{ url('') }}/admin/assets/images/product/{{ $np->img }}"
                                                                            width="100%" class="fixed-image"
                                                                            alt="">

                                                                        <span class="amount">
                                                                            @if ($np->variant->count() > 0)
                                                                                <span class="price">
                                                                                    <ins>
                                                                                        <span class="amount">Chỉ từ </span>
                                                                                    </ins>
                                                                                    {{ number_format($np->variant->min('discounted_price'), 0, ',', '.') . ' đ' }}
                                                                                @else
                                                                                    {{ number_format($np->discountedPrice, 0, ',', '.') . ' đ' }}
                                                                            @endif
                                                                        </span>

                                                                        </span>
                                                                        <!-- /.price -->
                                                                        <h2 class="woocommerce-loop-product__title">
                                                                            {{ $np->name }}
                                                                        </h2>
                                                                    </a>
                                                                    <div class="hover-area">
                                                                        <a class="button add_to_cart_button"
                                                                            href="{{ route('client.product.show', ['slug' => $np->slug]) }}"
                                                                            rel="nofollow">Xem chi tiết</a>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                            <!-- /.product-outer -->
                                                        </div>
                                                    </div>
                                                    <!-- .woocommerce -->
                                                </div>
                                                <!-- .container-fluid -->
                                            </div>
                                            <!-- .products-carousel -->
                                        </div>
                                        
                                    </div>
                                    <!-- .tab-content -->
                                </div>
                                <!-- .section-products-carousel-tabs-wrap -->
                            </section>
                            <!-- .section-products-carousel-tabs -->
                        </div>
                        <div class="fullwidth-notice stretch-full-width">
                            <div class="col-full">
                                <p class="message">Mua hàng ngay - nhận ngay ưu đãi .</p>
                            </div>
                            <!-- .col-full -->
                        </div>
                        <!-- .fullwidth-notice -->
                        <section class="section-top-categories section-categories-carousel" id="categories-carousel-1">
                            <header class="section-header">
                                <h4 class="pre-title">Danh mục</h4>
                                </h2>
                                <nav class="custom-slick-nav"></nav>
                                <!-- .custom-slick-nav -->
                            </header>
                            <!-- .section-header -->
                            <div class="product-categories-1 product-categories-carousel" data-ride="tm-slick-carousel"
                                data-wrap=".products"
                                data-slick="{&quot;slidesToShow&quot;:5,&quot;slidesToScroll&quot;:1,&quot;dots&quot;:false,&quot;arrows&quot;:true,&quot;prevArrow&quot;:&quot;&lt;a href=\&quot;#\&quot;&gt;&lt;i class=\&quot;tm tm-arrow-left\&quot;&gt;&lt;\/i&gt;&quot;,&quot;nextArrow&quot;:&quot;&lt;a href=\&quot;#\&quot;&gt;&lt;i class=\&quot;tm tm-arrow-right\&quot;&gt;&lt;\/i&gt;&quot;,&quot;appendArrows&quot;:&quot;#categories-carousel-1 .custom-slick-nav&quot;,&quot;responsive&quot;:[{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesToScroll&quot;:2}},{&quot;breakpoint&quot;:1400,&quot;settings&quot;:{&quot;slidesToShow&quot;:4,&quot;slidesToScroll&quot;:4}}]}">
                                <div class="woocommerce columns-5">
                                    <div class="products">
                                        @foreach ($categories as $category)
                                            <div class="product-category product">
                                                <a
                                                    href="{{ route('client.category.products', ['categoryId' => $category->id]) }}">
                                                    <h2 class="woocommerce-loop-category__title">
                                                        {{ $category->name }}
                                                    </h2>
                                                </a>
                                            </div>
                                        @endforeach

                                    </div>
                                    <!-- .products -->
                                </div>
                                <!-- .woocommerce -->
                            </div>
                            <!-- .product-categories-carousel -->
                        </section>
                        <!-- .section-categories-carousel -->
                        <section
                            style="background-size: cover; background-position: center center; background-image: url( admin/assets/images/banner/banner7.png ); height: 800px;"
                            class="section-landscape-full-product-cards-carousel">

                            <div class="col-full">
                                <header class="section-header">
                                    <h2 class="section-title">
                                        <strong>Tai nghe &amp; Âm thanh </strong>
                                    </h2>
                                </header>
                                <!-- .section-header -->
                                <div class="row">
                                    <div class="landscape-full-product-cards-carousel">
                                        <div class="products-carousel" data-ride="tm-slick-carousel"
                                            data-wrap=".products"
                                            data-slick="{&quot;rows&quot;:2,&quot;slidesPerRow&quot;:2,&quot;slidesToShow&quot;:1,&quot;slidesToScroll&quot;:1,&quot;dots&quot;:true,&quot;arrows&quot;:false,&quot;responsive&quot;:[{&quot;breakpoint&quot;:767,&quot;settings&quot;:{&quot;slidesPerRow&quot;:2,&quot;slidesToShow&quot;:1,&quot;slidesToScroll&quot;:1}},{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesPerRow&quot;:1,&quot;slidesToShow&quot;:1,&quot;slidesToScroll&quot;:1}}]}">
                                            <div class="container-fluid">
                                                @php
                                                    $HeadPhoneCateId = DB::table('product_categories')
                                                        ->where('id', 5)
                                                        ->value('id');
                                                    $HeadPhoneProducts = DB::table('products')
                                                        ->where('category_id', $HeadPhoneCateId)
                                                        ->get();
                                                @endphp
                                                {{-- {{$HeadPhoneCateId}} --}}
                                                <div class="woocommerce columns-2">
                                                    <div class="products">
                                                        @foreach ($HeadPhoneProducts as $HeadPhoneProduct)
                                                            @php
                                                                $promotion = $discountedProducts
                                                                    ->where('id', $HeadPhoneProduct->id)
                                                                    ->first();
                                                                $hasDiscount = $promotion ? true : false;
                                                                $discountedPrice = $hasDiscount
                                                                    ? $HeadPhoneProduct->base_price *
                                                                        (1 -
                                                                            $promotion->promotion->discount_percent /
                                                                                100)
                                                                    : null;
                                                            @endphp
                                                            <div class="landscape-product-card product" style="height: 350px;">

                                                                <div class="media">
                                                                    <a class="woocommerce-LoopProduct-link"
                                                                        href="{{ route('client.product.show', ['slug' => $HeadPhoneProduct->slug]) }}">
                                                                        <img class="wp-post-image" style="height: 200px; object-fit: cover;"
                                                                            src="{{ url('') }}/admin/assets/images/product/{{ $HeadPhoneProduct->img }}"
                                                                            alt="{{ $HeadPhoneProduct->name }}">
                                                                    </a>
                                                                    <div class="media-body">
                                                                        <a class="woocommerce-LoopProduct-link "
                                                                            href="{{ route('client.product.show', ['slug' => $HeadPhoneProduct->slug]) }}">
                                                                            <span class="price">
                                                                                @if ($hasDiscount == 1)
                                                                                    <ins>
                                                                                        <span
                                                                                            class="amount">{{ number_format($discountedPrice, 0, ',', '.') }}
                                                                                            đ</span>
                                                                                    </ins>
                                                                                    <del>
                                                                                        <span
                                                                                            class="amount">{{ number_format($HeadPhoneProduct->base_price, 0, ',', '.') }}
                                                                                            đ</span>
                                                                                    </del>
                                                                                @else
                                                                                    <span
                                                                                        class="amount">{{ number_format($HeadPhoneProduct->base_price, 0, ',', '.') }}
                                                                                        đ</span>
                                                                                @endif
                                                                            </span>
                                                                            <!-- .price -->
                                                                        <a href="{{ route('client.product.show', ['slug' => $HeadPhoneProduct->slug]) }}">
                                                                            <h2 class="woocommerce-loop-product__title">
                                                                                {{ $HeadPhoneProduct->name }}
                                                                            </h2>

                                                                            <div class="techmarket-product-rating">
                                                                                <span
                                                                                    class="review-count">Đã bán ({{ $HeadPhoneProduct->purchases }})</span>
                                                                            </div>
                                                                            <!-- .techmarket-product-rating -->
                                                                        </a>
                                                                        <div class="hover-area">
                                                                            <a class="button add_to_cart_button"
                                                                                href="{{ route('client.product.show', ['slug' => $HeadPhoneProduct->slug]) }}">Xem
                                                                                chi tiết</a>
                                                                            </a>
                                                                        </div>
                                                                        <!-- .hover-area -->
                                                                    </div>
                                                                    <!-- .media-body -->
                                                                </div>
                                                                <!-- .media -->
                                                            </div>
                                                        @endforeach
                                                        <!-- .woocommerce-LoopProduct-link -->
                                                    </div>
                                                    <!-- .products -->
                                                </div>
                                                <!-- .woocommerce -->
                                            </div>
                                            <!-- .container-fluid -->
                                        </div>
                                        <!-- .slick-dots -->
                                    </div>
                                    <!-- .landscape-full-product-cards-carousel -->
                                </div>
                                <!-- .row -->
                            </div>
                            <!-- .col-full -->
                        </section>
                        <!-- .slick-dots -->
                        <section class="section-hot-new-arrivals section-products-carousel-tabs techmarket-tabs">
                            <div class="section-products-carousel-tabs-wrap">
                                <header class="section-header">
                                    <h2 class="section-title">Sản phẩm bán chạy</h2>  
                                </header>
                                <br>
                                <!-- .section-header -->
                                <div class="tab-content">
                                    <div id="tab-59f89f08825d50" class="tab-pane active" role="tabpanel">
                                        <div class="products-carousel" data-ride="tm-slick-carousel"
                                            data-wrap=".products"
                                            data-slick="{&quot;infinite&quot;:false,&quot;slidesToShow&quot;:7,&quot;slidesToScroll&quot;:7,&quot;dots&quot;:true,&quot;arrows&quot;:false,&quot;responsive&quot;:[{&quot;breakpoint&quot;:700,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesToScroll&quot;:2}},{&quot;breakpoint&quot;:780,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesToScroll&quot;:3}},{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesToShow&quot;:4,&quot;slidesToScroll&quot;:4}},{&quot;breakpoint&quot;:1400,&quot;settings&quot;:{&quot;slidesToShow&quot;:5,&quot;slidesToScroll&quot;:5}}]}">
                                            <div class="container-fluid">
                                                <div class="woocommerce">
                                                    <div class="products">
                                                        @foreach ($hotproducts as $hp)
                                                            <div class="product">
                                                                <a href="{{ route('client.product.show', ['slug' => $hp->slug]) }}"
                                                                    class="woocommerce-LoopProduct-link">
                                                                    <img src="{{ url('') }}/admin/assets/images/product/{{ $hp->img }}"
                                                                        width="100%" class="fixed-image"
                                                                        alt="">
                                                                    <span class="price">
                                                                        @if ($hp->variant->count() > 0)
                                                                            <span class="price">
                                                                                <ins>
                                                                                    <span class="amount">Chỉ từ </span>
                                                                                </ins>
                                                                                {{ number_format($hp->variant->min('discounted_price'), 0, ',', '.') . ' đ' }}
                                                                            @else
                                                                                {{ number_format($hp->base_price, 0, ',', '.') . ' đ' }}
                                                                        @endif
                                                                        <br>
                                                                        <span class="amount"> Lượt mua :
                                                                            {{ $hp->purchases }}</span>
                                                                    </span>
                                                                    <!-- /.price -->
                                                                    <h2 class="woocommerce-loop-product__title">
                                                                        {{ $hp->name }}
                                                                    </h2>
                                                                </a>
                                                                <div class="hover-area">
                                                                    <a class="button add_to_cart_button"
                                                                        href="{{ route('client.cart.index') }}"
                                                                        rel="nofollow">Xem chi tiết</a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <!-- /.product-outer -->
                                                    </div>
                                                </div>
                                                <!-- .woocommerce -->
                                            </div>
                                            <!-- .container-fluid -->
                                        </div>
                                        <!-- .products-carousel -->
                                    </div>
                                    <!-- .tab-pane -->
                                    <!-- .tab-pane -->
                                </div>
                                <!-- .tab-content -->
                            </div>
                            <!-- .section-products-carousel-tabs-wrap -->
                        </section>
                        <!-- .section-products-carousel-tabs -->
                        <div class="banners">
                            <div class="row">
                                <div class="banner banner-long text-in-right">
                                    <a href="{{ route('client.product.index') }}">
                                        <div style="background-size: cover; background-position: center center; background-image: url( admin/assets/images/banner/image.png ); height: 290px;"
                                            class="banner-bg">
                                            {{-- <div class="caption">
                                                <div class="banner-info">
                                                    <h3 class="title">
                                                        <strong>Shop now</strong> to find savings on everything you need
                                                        <br> for the big game.
                                                    </h3>
                                                </div>
                                                <!-- /.banner-info -->
                                                <span class="banner-action button">Browse</span>
                                            </div> --}}
                                            <!-- /.caption -->
                                        </div>
                                        <!-- /.banner-bg -->
                                    </a>
                                </div>
                                <!-- /.banner -->
                                <div class="banner banner-short text-in-left">
                                    <a href="{{ route('client.product.index') }}">
                                        <div style="background-size: cover; background-position: center center; background-image: url( admin/assets/images/banner/image1.png); height: 290px; width: 290px"
                                            class="banner-bg">
                                            {{-- <div class="caption">
                                                <div class="banner-info">
                                                    <h3 class="title">
                                                        <strong>1000 mAh</strong>
                                                        <br> Power Bank Pro.
                                                    </h3>
                                                </div>
                                                <!-- /.banner-info -->
                                                <span class="price">$34.99</span>
                                                <span class="banner-action button">Buy Now</span>
                                            </div> --}}
                                            <!-- /.caption -->
                                        </div>
                                        <!-- /.banner-bg -->
                                    </a>
                                </div>
                                <!-- /.banner -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- .brands-carousel -->
                    </main>
                    <!-- #main -->
                </div>
                <!-- #primary -->
            </div>
            <!-- .row -->
        </div>
        <!-- .col-full -->
    </div>
@endsection

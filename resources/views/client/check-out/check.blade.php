@extends('client.layouts.master')

@section('main')
    <div id="content" class="site-content">
        <div class="col-full">
            <div class="row">
                <nav class="woocommerce-breadcrumb">
                    <a href="home-v1.html">Home</a>
                    <span class="delimiter">
                        <i class="tm tm-breadcrumbs-arrow-right"></i>
                    </span>
                    Checkout
                </nav>
                <!-- .woocommerce-breadcrumb -->
                <div class="content-area" id="primary">
                    <main class="site-main" id="main">
                        <div class="type-page hentry">
                            <div class="entry-content">
                                <div class="woocommerce">

                                    <!-- .collapse -->
                                    {{-- <div class="woocommerce-info">Have a coupon? <a data-toggle="collapse"
                                            href="#checkoutCouponForm" aria-expanded="false"
                                            aria-controls="checkoutCouponForm" class="showlogin">Click here to enter your
                                            code</a>
                                    </div>
                                    <div class="collapse" id="checkoutCouponForm">
                                        <form method="post" class="checkout_coupon">
                                            <p class="form-row form-row-first">
                                                <input type="text" value="" id="coupon_code" placeholder="Coupon code"
                                                    class="input-text" name="coupon_code">
                                            </p>
                                            <p class="form-row form-row-last">
                                                <input type="submit" value="Apply coupon" name="apply_coupon"
                                                    class="button">
                                            </p>
                                            <div class="clear"></div>
                                        </form>
                                    </div> --}}
                                    <!-- .collapse -->

                                    <form action="{{ route('client.checkout.store') }}"
                                        class="checkout woocommerce-checkout" method="post" name="checkout">
                                        @csrf
                                        <div id="customer_details" class="col2-set">
                                            <div class="col-1">
                                                <div class="woocommerce-billing-fields">
                                                    <h3>Thông tin chi tiết</h3>
                                                    <div class="woocommerce-billing-fields__field-wrapper-outer">
                                                        <div class="woocommerce-billing-fields__field-wrapper">
                                                            <p id="billing_first_name_field"
                                                                class="form-row form-row-first validate-required woocommerce woocommerce-invalid-required-field">
                                                                <label class="" for="billing_first_name">Họ và Tên
                                                                    Người nhận
                                                                    <abbr title="required" class="required">*</abbr>
                                                                    @error('full_name')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </label>
                                                                <input type="text"
                                                                    value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                                                    placeholder="" id="billing_first_name" name="full_name"
                                                                    class="input-text ">

                                                            </p>
                                                            <div class="clear"></div>
                                                            <p id="billing_city_field"
                                                                class="form-row form-row-last validate-required validate-city">
                                                                <label class="" for="billing_city">Thành phố
                                                                    <abbr title="required" class="required">*</abbr>
                                                                    @error('province_id')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </label>
                                                                <select name="province_id" id="province">
                                                                    <option value="" selected disabled>Chọn tỉnh/thành
                                                                        phố</option>
                                                                    @foreach ($provinces as $province)
                                                                        <option value="{{ $province->province_id }}">
                                                                            {{ $province->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                            </p>

                                                            <p id="billing_district_field"
                                                                class="form-row form-row-first validate-required validate-district">
                                                                <label class="" for="billing_district">Quận
                                                                    <abbr title="required" class="required">*</abbr>
                                                                    @error('district_id')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </label>
                                                                <select name="district_id" id="district">
                                                                    <option value="" selected disabled>Chọn quận/huyện
                                                                    </option>
                                                                </select>

                                                            </p>

                                                            <p id="billing_ward_field"
                                                                class="form-row form-row-last validate-required validate-ward">
                                                                <label class="" for="billing_ward">Phường/Xã
                                                                    <abbr title="required" class="required">*</abbr>
                                                                    @error('ward_code')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </label>
                                                                <select name="ward_code" id="ward">
                                                                    <option value="" selected disabled>Chọn phường/xã
                                                                    </option>
                                                                </select>

                                                            </p>

                                                            <p id="billing_phone_field"
                                                                class="form-row form-row-first validate-required validate-phone">
                                                                <label class="" for="billing_phone">Số điện thoại
                                                                    <abbr title="required" class="required">*</abbr>
                                                                    @error('phone')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </label>
                                                                <input type="tel"
                                                                    value="{{ auth()->check() ? auth()->user()->phone : '' }}"
                                                                    placeholder="" id="billing_phone" name="phone"
                                                                    class="input-text ">

                                                            </p>
                                                            <p id="billing_email_field"
                                                                class="form-row form-row-last validate-required validate-email">
                                                                <label class="" for="billing_email">Email
                                                                    <abbr title="required" class="required">*</abbr>
                                                                    @error('email')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </label>
                                                                <input type="email"
                                                                    value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                                                    placeholder="" id="billing_email" name="email"
                                                                    class="input-text ">

                                                            </p>
                                                        </div>
                                                    </div>
                                                    <!-- .woocommerce-billing-fields__field-wrapper-outer -->
                                                </div>
                                            </div>
                                            <!-- .col-1 -->
                                            <div class="col-2">
                                                <!-- .woocommerce-shipping-fields -->
                                                <div class="woocommerce-additional-fields">
                                                    <div class="woocommerce-additional-fields__field-wrapper">
                                                        <p id="order_comments_field" class="form-row notes">
                                                            <label class="" for="order_comments">Địa chỉ chi tiết
                                                                <abbr title="required" class="required">*</abbr>
                                                                @error('address')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </label>
                                                            <textarea cols="5" rows="2" placeholder="Viết địa chỉ chi tiết của quý khách" id="order_comments"
                                                                class="input-text " name="address">{{ auth()->check() ? auth()->user()->address : '' }}</textarea>
                                                        </p>
                                                    </div>
                                                    <!-- .woocommerce-additional-fields__field-wrapper-->
                                                </div>
                                                <!-- .woocommerce-additional-fields -->
                                            </div>
                                            <!-- .col-2 -->
                                        </div>
                                        <!-- .col2-set -->
                                        <h3 id="order_review_heading">Đơn hàng của bạn</h3>
                                        <div class="woocommerce-checkout-review-order" id="order_review">
                                            <div class="order-review-wrapper">
                                                <h3 class="order_review_heading">Đơn hàng của bạn</h3>
                                                <table class="shop_table woocommerce-checkout-review-order-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="product-name">Sản phẩm</th>
                                                            <th class="product-total">đơn giá</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($checkout['cartItems'] as $item)
                                                            <tr class="cart_item">
                                                                <td class="product-name">
                                                                    <strong class="product-quantity">{{ $item->quantity }}
                                                                        x </strong> {{ $item->product->name }}
                                                                    @if ($item->variant_id)
                                                                        {{ $item->attributes }}
                                                                    @endif
                                                                    <input type="hidden" name="variant_id"
                                                                        value="{{ $item->variant_id }}">
                                                                    <input type="hidden" name="quantity"
                                                                        value="{{ $item->quantity }}">
                                                                    <input type="hidden" name="price"
                                                                        value="{{ $item->discounted_price }}">


                                                                </td>
                                                                <td class="product-total">
                                                                    <span class="woocommerce-Price-amount amount">
                                                                        {{ number_format($item->discounted_price * $item->quantity, 0, ',', '.') }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="cart-subtotal">
                                                            <th>Sản phẩm</th>
                                                            <td>
                                                                <span class="woocommerce-Price-amount amount">
                                                                    {{ number_format($checkout['subtotal'], 0, ',', '.') }}<span
                                                                        class="woocommerce-Price-currencySymbol"></span>đ</span>
                                                            </td>
                                                        </tr>
                                                        <tr class="cart-subtotal">
                                                            <th>Giảm giá </th>
                                                            <td>
                                                                <span
                                                                    class="woocommerce-Price-amount amount">{{ number_format($checkout['discountAmount'], 0, ',', '.') }}<span
                                                                        class="woocommerce-Price-currencySymbol"></span>đ</span>
                                                            </td>
                                                        </tr>
                                                        <tr class="cart-subtotal">
                                                            <th>Phí vận chuyển </th>
                                                            <td>
                                                                <p id="shipping-fee">0</p>
                                                                <input type="hidden" name="fee_shipping">
                                                            </td>
                                                        </tr>
                                                        <tr class="order-total">
                                                            <th>Thành tiền</th>
                                                            <td>
                                                                <strong id="final-total-wrapper" style="display: none;">
                                                                    <span id="final-total"></span>
                                                                </strong>
                                                                <input type="hidden" name="total" id="total"
                                                                    value="{{ $checkout['total'] }}">
                                                                <input type="hidden" name="totalWeight" id="weight"
                                                                    value="{{ $checkout['totalWeight'] }}">
                                                                <input type="hidden" name="voucher"
                                                                    value="{{ $checkout['voucher']['code'] ?? null }}">
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <!-- /.woocommerce-checkout-review-order-table -->
                                                <div class="woocommerce-checkout-payment" id="payment">
                                                    <ul class="wc_payment_methods payment_methods methods">
                                                        <h3 id="order_review_heading">Phương thức thanh toán</h3>
                                                        <li class="wc_payment_method payment_method_bacs">
                                                            <input type="radio" data-order_button_text=""
                                                                checked="checked" value="1" name="payment_method"
                                                                class="input-radio" id="payment_method_bacs">
                                                            <label for="payment_method_bacs">VNPAY</label>
                                                        </li>
                                                        <li class="wc_payment_method payment_method_cod">
                                                            <input type="radio" data-order_button_text=""
                                                                value="2" name="payment_method" class="input-radio"
                                                                id="payment_method_cod">
                                                            <label for="payment_method_cod">Ship COD</label>

                                                        </li>
                                                    </ul>
                                                    <div class="form-row place-order">
                                                        <button type="submit" class="btn btn-primary btn-block">Xác nhận
                                                            đặt hàng</button>
                                                    </div>

                                                </div>
                                                <!-- /.woocommerce-checkout-payment -->
                                            </div>
                                            <!-- /.order-review-wrapper -->
                                        </div>
                                        <!-- .woocommerce-checkout-review-order -->
                                    </form>
                                    <!-- .woocommerce-checkout -->
                                </div>
                                <!-- .woocommerce -->
                            </div>
                            <!-- .entry-content -->
                        </div>
                        <!-- #post-## -->
                    </main>
                    <!-- #main -->
                </div>
                <!-- #primary -->
            </div>
            <!-- .row -->
        </div>
        <!-- .col-full -->
    </div>
    <!-- Modal cảnh báo đơn hàng trống -->
    <div class="modal fade" id="emptyCartModal" tabindex="-1" role="dialog" aria-labelledby="emptyCartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emptyCartModalLabel">Cảnh báo</h5>

                </div>
                <div class="modal-body">
                    Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi đặt hàng.
                </div>
                <div class="modal-footer">
                    <a href="{{ route('home') }}" class="btn btn-primary">Quay lại trang chủ</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal cảnh báo tổng quá 1 tỷ -->
    <div class="modal fade" id="limitWarningModal" tabindex="-1" role="dialog"
        aria-labelledby="limitWarningModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-info">
                <div class="modal-header">
                    <h5 class="modal-title" id="limitWarningModalLabel">Cảnh báo</h5>
                </div>
                <div class="modal-body text-danger">
                    Tổng giá trị đơn hàng đã vượt quá giới hạn cho phép (1 tỷ VNĐ). Vui lòng điều chỉnh lại để tiếp tục đặt
                    hàng.
                </div>
                <div class="modal-footer">
                    <a href="{{ route('client.cart.index') }}" class="btn btn-info">Quay lại trang giỏ hàng</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        window.baseAppUrl = "{{ env('APP_URL') }}";
        async function callApi(url, method = 'GET', data = {}) {
            try {
                const options = {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                };

                if (method !== 'GET') {
                    options.body = JSON.stringify(data);
                }

                const response = await fetch(url, options);

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                return await response.json();
            } catch (error) {
                console.error('API call failed:', error);
                return null;
            }
        }

        $(document).ready(function() {
            $('#province').change(function() {
                var province_id = $(this).val();
                console.log("Province ID: ", province_id);

                $('#district').empty().append(
                    '<option value="" selected disabled>Chọn quận/huyện</option>');
                $('#ward').empty().append(
                    '<option value="" selected disabled>Chọn phường/xã</option>');

                if (province_id) {
                    $.ajax({
                        url: 'checkout/get-districts/' + province_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(key, value) {
                                    $('#district').append('<option value="' +
                                        value
                                        .district_id +
                                        '">' + value.name + '</option>');
                                });
                            } else {
                                alert("Không tìm thấy quận/huyện cho tỉnh đã chọn!");
                            }
                        },
                        error: function() {
                            alert("Lỗi khi tải danh sách quận/huyện.");
                        }
                    });
                }
            });

            $('#district').change(function() {
                var district_id = $(this).val();
                console.log("District ID: ", district_id);

                $('#ward').empty().append(
                    '<option value="" selected disabled>Chọn phường/xã</option>');

                if (district_id) {
                    $.ajax({
                        url: 'checkout/get-wards/' + district_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(key, value) {
                                    $('#ward').append('<option value="' + value
                                        .code +
                                        '">' + value.name + '</option>');
                                });
                            } else {
                                alert(
                                    "Không tìm thấy phường/xã cho quận/huyện đã chọn!");
                            }
                        },
                        error: function() {
                            alert("Lỗi khi tải danh sách phường/xã.");
                        }
                    });
                }
            });

            $('#ward').change(function() {
                console.log("Ward ID: ", $(this).val());
            });

            var cartItems = @json($checkout['cartItems']);
            if (cartItems.length === 0) {
                $('#emptyCartModal').modal('show');
            }

            $('form.checkout').on('submit', function(e) {
                if (cartItems.length === 0) {
                    e.preventDefault();
                    $('#emptyCartModal').modal('show');
                }
            });

            $('#province, #district, #ward').change(function() {
                $(this).siblings('.text-danger').remove();
            });

            async function calculateShippingFee(total, districtId, wardId, weight) {
                if (total && districtId && wardId && weight) {
                    const shippingFeeElement = $('#shipping-fee');
                    const finalTotalElement = $('#final-total');
                    const finalTotalWrapper = $('#final-total-wrapper');
                    const submitButton = $('.place-order button[type="submit"]');

                    try {
                        const response = await callApi(window.baseAppUrl + '/cart/calculate-shipping-fee', 'POST', {
                            _token: '{{ csrf_token() }}',
                            total: total,
                            district_id: districtId,
                            ward_id: wardId,
                            weight: weight
                        });

                        if (response && response.shippingFee !== undefined) {
                            const shippingFee = response.shippingFee;
                            const finalTotal = total + shippingFee;

                            console.log("Shipping Fee: ", shippingFee);

                            shippingFeeElement.text(new Intl.NumberFormat('vi-VN').format(shippingFee) + ' ₫');
                            finalTotalElement.text(new Intl.NumberFormat('vi-VN').format(finalTotal) + ' ₫');
                            $('input[name="fee_shipping"]').val(shippingFee);
                            finalTotalWrapper.fadeIn();

                            if (finalTotal > 1000000000) {
                                submitButton.prop('disabled', true);
                                $('#limitWarningModal').modal('show');
                            } else {
                                submitButton.prop('disabled', false);
                            }
                        } else {
                            alert("Không nhận được phí vận chuyển từ máy chủ.");
                        }
                    } catch (error) {
                        alert("Đã xảy ra lỗi khi tính phí vận chuyển.");
                        console.error(error);
                    }
                }
            }

            $('#province, #district, #ward').change(function() {
                const total = parseInt($('#total').val());
                const districtId = $('#district').val();
                const wardId = $('#ward').val();
                const weight = $('#weight').val();

                calculateShippingFee(total, districtId, wardId, weight);
            });
        });
    </script>
@endsection

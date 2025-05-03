@extends('client.layouts.master')

@section('main')
    <div id="content" class="site-content">
        <div class="col-full">
            <div class="row">
                <nav class="woocommerce-breadcrumb">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <span class="delimiter">
                        <i class="tm tm-breadcrumbs-arrow-right"></i>
                    </span>
                    Giỏ hàng
                </nav>
                <!-- .woocommerce-breadcrumb -->
                <div id="primary" class="content-area">
                    <main id="main" class="site-main">
                        <div class="type-page hentry">
                            <div class="entry-content">
                                <div class="woocommerce">
                                    <div class="cart-wrapper">
                                        <form method="" action="" class="woocommerce-cart-form">
                                            @csrf
                                            <table class="shop_table shop_table_responsive cart">
                                                @if ($cartItems->isEmpty())
                                                    <p class="text-center text-muted">Giỏ hàng trống</p>
                                                    <a class="button" href="{{ route('home') }}">Tiếp tục mua hàng
                                                    </a>
                                                @else
                                                    <thead>
                                                        <tr>
                                                            <th class="product-remove">&nbsp;</th>
                                                            <th class="product-thumbnail">&nbsp;</th>
                                                            <th class="product-name">Sản phẩm</th>
                                                            <th class="product-price">Giá</th>
                                                            <th class="product-quantity">Số lượng</th>
                                                            <th class="product-subtotal">Tổng tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($cartItems as $cart)
                                                            <tr class="cart-item">
                                                                <td class="product-remove">
                                                                </td>
                                                                <td class="product-thumbnail">
                                                                    <a
                                                                        href="{{ route('client.product.show', ['slug' => $cart->product->slug]) }}">
                                                                        <img width="180" height="180" alt=""
                                                                            class="wp-post-image" src=" ">
                                                                    </a>
                                                                </td>
                                                                <td data-title="Product" class="product-name">
                                                                    <div class="media cart-item-product-detail">
                                                                        <!-- Thẻ div chứa ảnh -->

                                                                        <div class="product-image-wrapper d-flex justify-content-start align-items-center"
                                                                            style="max-width: 100px; max-height: 100px; margin-left: 15px;">
                                                                            <a
                                                                                href="{{ route('client.product.show', ['slug' => $cart->product->slug]) }}">
                                                                                <img width="100" alt=""
                                                                                    class="img-fluid align-self-center"
                                                                                    style="max-width: 100%; max-height: 100px; object-fit: contain; "
                                                                                    src="{{ url('') }}/admin/assets/images/product/{{ $cart->product->img }}">
                                                                            </a>
                                                                        </div>



                                                                        <!-- Thẻ div chứa thông tin sản phẩm -->
                                                                        <div class="media-body align-self-center">
                                                                            <a
                                                                                href="{{ route('client.product.show', ['slug' => $cart->product->slug]) }}">{{ $cart->product->name }}
                                                                                @if ($cart->variant_id)
                                                                                    {{ $cart->attributes }}
                                                                                @endif
                                                                            </a>
                                                                            <p data-title="Stock" class="product-stock">
                                                                                <span>Tồn Kho :</span>
                                                                                <span
                                                                                    class="stock-quantity-{{ $cart->id }}">
                                                                                    {{ $cart->stock - $soldQuantity }}
                                                                                </span>
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                                <td data-title="Price" class="product-price">
                                                                    <span class="woocommerce-Price-amount amount">
                                                                        <span
                                                                            class="woocommerce-Price-currencySymbol">{{ number_format($cart->discounted_price, 0, ',', '.') }}
                                                                            đ</span>
                                                                    </span>
                                                                </td>
                                                                <td class="product-quantity" data-title="Quantity">
                                                                    <div class="quantity">
                                                                        <label for="quantity-input-1">Số lượng</label>
                                                                        <input id="quantity-input-{{ $cart->id }}"
                                                                            type="number" name="quantity"
                                                                            value="{{ $cart->quantity }}"
                                                                            class="input-text qty text update-cart"
                                                                            data-id="{{ $cart->id }}" min="1"
                                                                            max="{{ $cart->stock }}">
                                                                    </div>
                                                                </td>
                                                                <td data-title="Total" class="product-subtotal">
                                                                    <span
                                                                        class="woocommerce-Price-amount amount total-price-{{ $cart->id }}">
                                                                        {{ number_format($cart->discounted_price * $cart->quantity, 0, ',', '.') }}
                                                                        đ
                                                                    </span>
                                                                    <a class="remove" href="#"
                                                                        data-id="{{ $cart->id }}">X</a>


                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                @endif
                                                @if (!$cartItems->isEmpty())
                                                    <tr>
                                                        <td class="actions" colspan="6">
                                                            <div class="voucher">
                                                                <label for="voucher_code">Voucher:</label>
                                                                <select name="voucher_code" id="voucher_code"
                                                                    class="input-text selectpicker" data-live-search="true">
                                                                    <option value="" disabled selected> Chọn voucher
                                                                        hiện cóㅤㅤ </option>
                                                                    @foreach ($voucherList as $item)
                                                                        <option value="{{ $item->code }}"
                                                                            {{ isset($voucherused) && $voucherused->code === $item->code ? 'selected' : '' }}>
                                                                            {{ $item->code }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>
                                                                <button type="submit" id="apply-voucher" class="button">Sử
                                                                    dụng</button>
                                                            </div>

                                                            <p id="voucher-error" class="text-danger small"></p>
                                                            <p id="voucher-success" class="text-success small"></p>
                                                            <a class="button"
                                                                href="{{ route('client.product.index') }}">Tiếp tục mua
                                                                hàng
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif

                                                </tbody>
                                            </table>
                                            <!-- .shop_table shop_table_responsive -->
                                        </form>
                                        <!-- .woocommerce-cart-form -->
                                        <div class="cart-collaterals">
                                            <div class="cart_totals">
                                                <h2>Tổng tiền</h2>
                                                <table class="shop_table shop_table_responsive">
                                                    <tbody>
                                                        <tr class="cart-subtotal">
                                                            <th>Sản phẩm</th>
                                                            <td data-title="Subtotal">
                                                                <span class="woocommerce-Price-amount amount">
                                                                    <span
                                                                        class="subtotal-price">{{ number_format($subtotal, 0, ',', '.') . ' đ' }}</span>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @if ($discountAmount > 0)
                                                            <tr class="cart-subtotal">
                                                                <th>Giảm giá</th>
                                                                <td data-title="Discount">
                                                                    <span class="woocommerce-Price-amount amount">
                                                                        <span
                                                                            class="discount-amount">{{ number_format($discountAmount, 0, ',', '.') . ' đ' }}</span>

                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr class="cart-subtotal">
                                                                <th>Giảm giá</th>
                                                                <td data-title="Discount">
                                                                    <span class="woocommerce-Price-amount amount">
                                                                        <span class="discount-amount">0</span>

                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr class="order-total">
                                                            <th>Thanh toán</th>
                                                            <td data-title="Total">
                                                                <strong>
                                                                    <span class="woocommerce-Price-amount amount">
                                                                        <span
                                                                            class="total-price">{{ number_format($total, 0, ',', '.') . ' đ' }}</span>

                                                                    </span>
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="wc-proceed-to-checkout">
                                                    <a class="checkout-button button alt wc-forward"
                                                        href="{{ route('client.checkout.index') }}">Thanh toán</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- .cart-collaterals -->
                                    </div>
                                    <!-- .cart-wrapper -->
                                </div>
                                <!-- .woocommerce -->
                            </div>
                            <!-- .entry-content -->
                        </div>
                        <!-- .hentry -->
                    </main>
                    <!-- #main -->
                </div>
                <!-- #primary -->
            </div>
            <!-- .row -->
        </div>
        <!-- .col-full -->
    </div>

    <!-- MODAL OVERRATE -->
    <div class="modal fade" id="stockWarningModal" tabindex="-1" role="dialog"
        aria-labelledby="stockWarningModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockWarningModalLabel"><span class="bi-exclamation-triangle-fill text-warning"></span> Cảnh báo</h5>
                </div>
                <div class="modal-body">
                    <p>
                        Bạn đã nhập số lượng sản phẩm <span class="text-danger">không hợp lệ</span> .
                        <br>
                        Tồn kho hiện tại của <span id="maxStockName"></span> : <span id="maxStockValue"></span> sản phẩm

                    </p>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- #content -->

@endsection
@section('cartScripts')
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
    let appliedVoucher = "";
    let previousQuantities = {};

    $(document).on("focusin", ".update-cart", function() {
        let cartId = $(this).data("id");
        previousQuantities[cartId] = $(this).val();
    });

    async function updateCart(cartId, quantity, variantId = null, productId = null) {
        let maxStock = parseInt($(".stock-quantity-" + cartId).text(), 10);
        let previous = previousQuantities[cartId] || 1;

        if (quantity > maxStock || quantity < 1) {
            $("#maxStockValue").text(maxStock);
            $("#maxStockName").text($("#quantity-input-" + cartId).closest("tr").find(".product-name a").text());
            $("#quantity-input-" + cartId).val(previous);
            $("#stockWarningModal").modal("show");
            return;
        }

        let data = {
            _token: '{{ csrf_token() }}',
            id: cartId,
            quantity: quantity
        };

        if (variantId) data.variant_id = variantId;
        if (productId) data.product_id = productId;

        try {
            const response = await callApi(window.baseAppUrl + '/cart/cart/update', "POST", data);
            if (response.success) {
                $(".total-price-" + cartId).text(response.new_total_price);
                $(".subtotal-price").text(response.subtotal);
                $(".total-price").text(response.total);
                if (response.discount_amount) {
                    $(".discount-amount").text(response.discount_amount);
                }
                previousQuantities[cartId] = quantity;
            } else {
                $("#maxStockValue").text(response.message);
                $("#stockWarningModal").modal("show");
            }
        } catch (err) {
            $("#maxStockValue").text("Có lỗi xảy ra, vui lòng thử lại!");
            $("#stockWarningModal").modal("show");
        }
    }

    async function applyVoucher(voucherCode) {
        try {
            const response = await callApi(window.baseAppUrl + '/cart/applyVoucher', "POST", {
                _token: '{{ csrf_token() }}',
                voucher_code: voucherCode
            });

            if (response.success) {
                $(".subtotal-price").text(response.total_before_discount);
                $(".discount-amount").text(response.discount_amount);
                $(".total-price").text(response.total_after_discount);
                $("#voucher-error").text("");
                $("#voucher-success").text(response.message);
                appliedVoucher = voucherCode;
            } else {
                resetVoucher();
                $("#voucher-error").text(response.message);
            }
        } catch (err) {
            resetVoucher();
            $("#voucher-error").text("Voucher không hợp lệ hoặc giá trị đơn hàng chưa đạt mức tối thiểu");
        }
    }

    function resetVoucher() {
        $(".discount-amount").text("0");
        let subtotalText = $(".subtotal-price").text();
        $(".total-price").text(subtotalText);
        $("#voucher-success").text("");
        appliedVoucher = "";
    }

    $("#apply-voucher").on("click", function(e) {
        e.preventDefault();
        let voucherCode = $("#voucher_code").val().trim();
        applyVoucher(voucherCode);
    });

    $("#voucher_code").on("input", function() {
        let currentVoucher = $(this).val().trim();
        if (currentVoucher !== appliedVoucher) {
            resetVoucher();
        }
    });

    $(".update-cart").on("change", function() {
        let cartId = $(this).data("id");
        let quantity = $(this).val();
        let variantId = $(this).data("variant-id") || null;
        let productId = $(this).data("product-id") || null;
        updateCart(cartId, quantity, variantId, productId);
    });

    $(".remove").on("click", async function(e) {
        e.preventDefault();
        var cartId = $(this).data("id");
        var row = $(this).closest("tr");

        // var removeUrl = "{{ route('client.cart.remove', ':id') }}".replace(":id", cartId);
        var removeUrl = window.baseAppUrl + 'cart/remove/' + cartId;
        try {
            const response = await callApi(removeUrl, "POST", {
                _token: '{{ csrf_token() }}'
            });

            if (response.success) {
                row.fadeOut(300, function() {
                    $(this).remove();
                    if ($("tr.cart-item").length === 0) {
                        $(".shop_table").html('<p class="text-center text-muted">Giỏ hàng trống</p>');
                    }
                });
                $(".subtotal-price").text(response.subtotal);
                $(".total-price").text(response.total);
                $(".discount-amount").text(response.discount_amount || "0");
            } else {
                alert(response.message);
            }
        } catch (err) {
            alert("Có lỗi xảy ra, vui lòng thử lại!");
        }
    });
});

    </script>
@endsection

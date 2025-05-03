@extends('client.layouts.master')

@section('main')
    <div class="container mt-5">
        <h2 class="mb-4"> Thông Tin Đặt Hàng</h2>
        <style>
            input,
            select,
            textarea {
                width: 100%;
                padding: 10px;
                margin: 5px 0 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            input:focus,
            select:focus,
            textarea:focus {
                border-color: #007bff;
                outline: none;
            }

            button {
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            button:hover {
                background-color: #0056b3;
            }

            .flex-row {
                display: flex;
                gap: 15px;
            }

            .flex-row>p {
                flex: 1;
            }
        </style>
        @php
            $statusText = match ($order->status_id) {
                0 => 'Đã hủy đơn',
                1 => 'Đang xử lý',
                2 => 'Đang giao',
                3 => 'Đã giao',
                4 => 'Giao hàng thành công',
                5 => 'Yêu cầu hoàn hàng đang xét duyệt',
                6 => 'Đã xét duyệt hoàn hàng',
                7 => 'Hoàn hàng thành công',
                8 => 'Hoàn hàng thất bại',
                default => 'Không xác định',
            };

            $paymentText = match ($order->payment_method) {
                1 => 'VNPAY',
                2 => 'Ship COD',
                default => 'Không xác định',
            };
        @endphp

        <form action="" method="POST">
            @csrf
            @method('POST')
            <div id="customer_details">
                <div>
                    <div class="flex-row">
                        <p>
                            <label for="billing_first_name">Họ và Tên Người nhận</label>
                            <input type="text" value="{{ $order->full_name }}" id="billing_first_name" name="full_name"
                                disabled>
                        </p>
                        <p>
                            <label for="billing_phone">Số điện thoại</label>
                            <input type="tel" value="{{ $order->phone }}" id="billing_phone" name="phone" disabled>
                        </p>
                    </div>
                    <div class="flex-row">
                        <p>
                            <label for="order_id">Mã đơn hàng</label>
                            <input type="text" value="{{ $order->order_id }}" id="order_id" name="order_id" disabled>
                        </p>
                        <p>
                            <label for="order_status">Trạng thái đơn</label>
                            <input type="text" value="{{ $statusText }}" id="order_status" name="order_status"
                                disabled>
                        </p>
                    </div>
                    <div class="flex-row">
                        <p>
                            <label for="payment_method">Phương thức thanh toán</label>
                            <input type="text" value="{{ $paymentText }}" id="payment_method" name="payment_method"
                                disabled>
                        </p>
                        <p>
                            <label for="voucher">Voucher đã áp dụng</label>
                            <input type="text" value="{{ $order->voucher_code ?? 'Không áp dụng' }}" id="voucher"
                                name="voucher" disabled>
                        </p>
                    </div>
                    <div class="flex-row">
                        <p>
                            <label for="shipping_fee">Phí vận chuyển</label>
                            <input type="text" value="{{ number_format($order->fee_shipping, 0, ',', '.') }} VNĐ"
                                id="shipping_fee" name="shipping_fee" disabled>
                        </p>
                        <p>
                            <label for="order_comments">Địa chỉ chi tiết</label>
                            <input type="text" value="{{ $order->address }}" id="order_comments" name="address" disabled>
                        </p>
                    </div>

                    <h4>Chi tiết sản phẩm</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Khuyến mãi</th>
                                <th>Tổng tiền sản phẩm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->billDetails as $detail)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="{{ url('') }}/admin/assets/images/product/{{ $detail->product->img }}"
                                                alt="{{ $detail->product->name }}"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                            <span>{{ $detail->product->name }}{{ $detail->attributes ? ' (' . $detail->attributes . ')' : '' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ $detail->has_promotion ? 'Có khuyến mãi' : 'Không khyến mãi' }}</td>
                                    <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><strong>Thành tiền (bao gồm phí vận chuyển/mã voucher nếu có):</strong>
                                </td>
                                <td><strong>{{ number_format($order->total, 0, ',', '.') }} VNĐ</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ route('client.orders') }}" class="btn btn-secondary">Quay lại</a>
        </form>

    </div>
@endsection

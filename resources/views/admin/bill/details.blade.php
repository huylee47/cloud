@extends('admin.layouts.master')

@section('styles')
    <link rel="stylesheet" href="{{ url('') }}/admin/assets/css/custom.css">
@endsection

@section('main')
    <div id="main">
        <header class="col-md-4 mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">
            <section class="section">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="card-title">Chi Tiết Hóa Đơn</h4>
                                        <span>Trạng thái đơn hàng:</span>
                                        @if ($bill->status_id == 0)
                                            <span class="badge bg-dangẻ">Đã huỷ đơn</span>
                                        @elseif ($bill->status_id == 1)
                                            <span class="badge bg-warning">Chờ xử lý</span>
                                        @elseif ($bill->status_id == 2)
                                            <span class="badge bg-info">Đang giao</span>
                                        @elseif ($bill->status_id == 3)
                                            <span class="badge bg-success">Đã giao</span>
                                        @elseif ($bill->status_id == 4)
                                            <span class="badge bg-success">Đã nhận hàng</span>
                                        @elseif ($bill->status_id == 5)
                                            <span class="badge bg-secondary">Yêu cầu Hoàn đơn</span>
                                        @elseif ($bill->status_id == 6)
                                            <span class="badge bg-secondary">Xác nhận Hoàn đơn</span>
                                        @elseif ($bill->status_id == 7)
                                            <span class="badge bg-secondary">Hoàn đơn thành công</span>
                                        @else
                                            <span class="badge bg-secondary">Hoàn đơn thất bại</span>
                                        @endif

                                        @if ($bill->payment_method == 1)
                                            <span class="badge bg-success">VNPAY/Banking</span>
                                        @elseif ($bill->payment_method == 2)
                                            <span class="badge bg-success">COD</span>
                                        @else
                                            <span class="badge bg-success">Thanh toán tại quầy</span>
                                        @endif

                                        @if ($bill->payment_status == 1)
                                            <span class="badge bg-success">Đã thanh toán</span>
                                        @else
                                            <span class="badge bg-warning">Chưa thanh toán</span>
                                        @endif
                                        @if ($bill->voucher_code)
                                            <span class="badge bg-success"> Áp dụng Voucher</span>
                                        @endif
                                    </div>

                                    <div class="ms-auto">
                                        <a class="btn btn-primary" href="{{ route('admin.bill.index') }}">Quay lại</a>

                                        @if ($bill->status_id == 1)
                                            @if ($bill->payment_method != 0)
                                                <button class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#exportModal">Xác nhận đơn</button>
                                            @else
                                                <button class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#comfirmModal">Xác nhận thanh toán</button>
                                            @endif

                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#cancelModal">Huỷ đơn</button>
                                        @elseif($bill->payment_method != 0 && $bill->status_id == 2)
                                            <button class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#orderModal">Xác nhận giao hàng</button>
                                                @elseif($bill->status_id == 3 && $bill->user_id == null)
                                                <button class="btn btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#confirmGuestRefundModal">Xác nhận hoàn đơn</button>
                                        @elseif($bill->status_id == 5)
                                            <button class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#confirmRefundModal">Xác nhận hoàn đơn</button>
                                        @elseif($bill->status_id == 6)
                                            <button class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#sucessRefundModal">Hoàn đơn thành công</button>
                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#declineRefundModal">Hoàn đơn thất bại</button>
                                        @endif
                                        {{-- {{ $bill->payment_method }}{{$bill->status_id }} --}}


                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- Thông tin hóa đơn --}}
                                <div class="row">
                                    @if (!empty($bill->order_id))
                                        <div class="col-md-3 mb-3">
                                            <label for="order_id">Mã Hóa Đơn</label>
                                            <input type="text" class="form-control" id="order_id"
                                                value="{{ $bill->order_id }}" readonly>
                                        </div>
                                    @else
                                        <div class="col-md-3 mb-3">
                                            <label for="order_id">Tra cứu hoá đơn bằng SĐT</label>
                                            <input type="text" class="form-control" id="order_id"
                                                value="{{ $bill->phone }}" readonly>
                                        </div>
                                    @endif

                                    <div class="col-md-3 mb-3">
                                        <label for="full_name">Tên khách hàng</label>
                                        <input type="text" class="form-control" id="full_name"
                                            value="{{ $bill->full_name }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="email">Số điện thoại</label>
                                        <input type="text" class="form-control" id="email"
                                            value="{{ $bill->phone }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="email">E-mail</label>
                                        <input type="text" class="form-control" id="email"
                                            value="{{ $bill->email }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="created_at">Ngày tạo</label>
                                        <input type="text" class="form-control" id="created_at"
                                            value="{{ $bill->created_at }}" readonly>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="address">Tỉnh/Thành phố</label>
                                        <input type="text" class="form-control" id="address"
                                            value="{{ $bill->province->name }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="address">Quận/Huyện</label>
                                        <input type="text" class="form-control" id="address"
                                            value="{{ $bill->district->name }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="address">Xã/Phường</label>
                                        <input type="text" class="form-control" id="address"
                                            value="{{ $bill->ward->name }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="address">Địa chỉ chi tiết</label>
                                        <input type="text" class="form-control" id="address"
                                            value="{{ $bill->address }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="address">Voucher sử dụng ?</label>
                                        <input type="text" class="form-control" id="address"
                                            value="{{ $bill->voucher_code ?? 'Không sử dụng' }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="address">Phí vận chuyển</label>
                                        <input type="text" class="form-control" id="address"
                                            value="{{ number_format($bill->fee_shipping, 0, ',', '.') }} đ" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="total">Tổng tiền</label>
                                        <input type="text" class="form-control" id="total"
                                            value="{{ number_format($bill->total, 0, ',', '.') }} đ" readonly>
                                    </div>
                                    @if ($bill->status_id == 0 || $bill->status_id > 4 )
                                        <div class=" mb-3">
                                            <label for="total">Ghi chú đơn hàng</label>
                                            <input type="text" class="form-control" id="total"
                                                value="{{ $bill->note }}" readonly>
                                        </div>
                                    @endif

                                </div>

                                <h5 class="mt-4">Chi Tiết Hoá đơn</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên Sản Phẩm</th>
                                            <th>Số Lượng</th>
                                            <th>Giá bán</th>
                                            <th>Khuyến mại</th>
                                            <th>Tổng tiền của sản phẩm </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($billDetails as $billDetail)
                                            @php
                                                $isPromotionActive = $productPromotions->contains(function (
                                                    $promotion,
                                                ) use ($billDetail) {
                                                    return $promotion->product_id == $billDetail->product->id &&
                                                        $billDetail->created_at <= $promotion->end_date;
                                                });
                                            @endphp
                                            <tr>
                                                <td>
                                                    <img src="{{ url('') }}/admin/assets/images/product/{{ $billDetail->product->img }}"
                                                        alt="{{ $billDetail->product->img }}" class="img-fluid"
                                                        style="max-width: 100px; height: auto;">
                                                    {{ $billDetail->product->name }} {{ $billDetail->attributes }}
                                                </td>
                                                <td>{{ $billDetail->quantity }}</td>
                                                <td>{{ number_format($billDetail->price) }} đ</td>
                                                <td>{{ $isPromotionActive ? 'Có' : 'Không' }}</td>

                                                <td>
                                                    @if ($isPromotionActive)
                                                        {{ number_format($billDetail->variant ? $billDetail->variant->discounted_price * $billDetail->quantity : $billDetail->product->discounted_price * $billDetail->quantity) }}
                                                        đ
                                                    @else
                                                        {{ number_format($billDetail->variant ? $billDetail->price * $billDetail->quantity : $billDetail->product->base_price * $billDetail->quantity) }}
                                                        đ
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>

    {{-- Modal Xác Nhận Huỷ Đơn --}}
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Xác nhận Huỷ Đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn huỷ đơn hàng này không?</p>
                    <form id="cancelForm" action="{{ route('admin.bill.cancel', $bill->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="cancelNote" class="form-label">Ghi chú huỷ đơn <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="cancelNote" name="note" rows="3" required minlength="10"
                                placeholder="Nhập lý do huỷ đơn..."></textarea>
                            <div class="invalid-feedback">Ghi chú phải có ít nhất 10 ký tự.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger" id="confirmCancel" disabled>Xác nhận huỷ</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Xác Nhận Xuất Đơn --}}
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Xác Nhận Đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xuất đơn hàng này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <a href="{{ route('admin.bill.invoice', $bill->id) }}" class="btn btn-success">Xác nhận đơn</a>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Xác Nhận Thanh Toán Đơn --}}
    <div class="modal fade" id="comfirmModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Xác Nhận Đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xác nhận đơn hàng này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <a href="{{ route('admin.bill.direct', $bill->id) }}" class="btn btn-success">Xác nhận đơn</a>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Xác Nhận Giao hàng --}}
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Xác nhận giao hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Xác nhận giao hàng thành công ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <a href="{{ route('admin.bill.confirm', $bill->id) }}" class="btn btn-success">Xác nhận </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Xác nhận hoàn đơn khách vãng lai -->
    <div class="modal fade" id="confirmGuestRefundModal" tabindex="-1" aria-labelledby="confirmGuestRefundModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="refundForm" action="{{ route('admin.bill.confirmGuestReturnRequest', $bill->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmGuestRefundModalLabel">Xác nhận yêu cầu hoàn đơn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xác nhận yêu cầu hoàn đơn này không?</p>
                        <strong class="text-danger">
                            Lưu ý: Đây là yêu cầu hoàn đơn cho khách vãng lai, hãy chắc chắn rằng khách đã liên lạc trước đó.
                        </strong>
                        <div class="mb-3 mt-3">
                            <label for="refundNote" class="form-label">Ghi chú hoàn đơn <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="refundNote" name="note" rows="3" required minlength="10"
                                      placeholder="Nhập lý do hoàn đơn..."></textarea>
                            <div class="invalid-feedback">Ghi chú phải có ít nhất 10 ký tự.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-warning" id="confirmRefund" disabled>Xác nhận</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    
    
<!-- Modal Xác nhận hoàn đơn -->
<div class="modal fade" id="confirmRefundModal" tabindex="-1" aria-labelledby="confirmRefundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.bill.confirmReturnRequest', $bill->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRefundModalLabel">Xác nhận yêu cầu hoàn đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xác nhận yêu cầu hoàn đơn này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-warning">Xác nhận</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hoàn đơn thành công -->
<div class="modal fade" id="sucessRefundModal" tabindex="-1" aria-labelledby="sucessRefundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.bill.completeReturn', $bill->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sucessRefundModalLabel">Xác nhận hoàn đơn thành công</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xác nhận hoàn đơn thành công không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-success">Xác nhận thành công</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hoàn đơn thất bại -->
<div class="modal fade" id="declineRefundModal" tabindex="-1" aria-labelledby="declineRefundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.bill.failReturn', $bill->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineRefundModalLabel">Xác nhận hoàn đơn thất bại</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xác nhận hoàn đơn thất bại không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-danger">Xác nhận thất bại</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cancelNote = document.getElementById("cancelNote");
            const confirmCancel = document.getElementById("confirmCancel");
            const cancelForm = document.getElementById("cancelForm");

            cancelNote.addEventListener("input", function() {
                if (cancelNote.value.trim().length >= 10) {
                    confirmCancel.removeAttribute("disabled");
                } else {
                    confirmCancel.setAttribute("disabled", "true");
                }
            });

            confirmCancel.addEventListener("click", function() {
                cancelForm.submit();
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const refundNote = document.getElementById("refundNote");
            const confirmRefund = document.getElementById("confirmRefund");
    
            refundNote.addEventListener("input", function () {
                if (refundNote.value.trim().length >= 10) {
                    confirmRefund.removeAttribute("disabled");
                } else {
                    confirmRefund.setAttribute("disabled", "true");
                }
            });
        });
    </script>
    
@endsection

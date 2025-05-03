@extends('admin.layouts.master')

@section('main')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Chỉnh sửa tài khoản</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Quản lý tài khoản</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Chi tiết tài khoản #{{ $user->id }}</h4>
                        <span class="badge bg-{{ $user->status ? 'success' : 'danger' }}">
                            {{ $user->status ? 'Hoạt động' : 'Đã khóa' }}
                        </span>
                    </div>
                    @if($user->username === 'admin')
                        <span class="badge bg-primary mt-2">Admin gốc</span>
                    @elseif($user->role_id == 1)
                        <span class="badge bg-info mt-2">Quản trị viên</span>
                    @endif
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST" id="editForm">
                        @csrf @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-section mb-4">
                                    <h6 class="section-title bg-light p-2 mb-3">
                                        <i class="bi bi-person-badge me-2"></i>Thông tin cá nhân
                                    </h6>

                                    <div>
                                        <input type="text" hidden value="{{ $user->name }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tên đầy đủ</label>

                                        <input type="text" class="form-control bg-light @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name', $user->name) }}" readonly>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
    
                                    <div>
                                        <input type="text" hidden value="{{ $user->username }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tên đăng nhập</label>
                                        <input type="text" class="form-control bg-light" value="{{ $user->username }}" readonly>
                                        {{-- <small class="text-muted">Không thể thay đổi tên đăng nhập</small> --}}
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Ngày sinh</label>
                                                <input type="date" class="form-control bg-light" name="dob" 
                                                       value="{{ old('dob', $user->dob) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Giới tính</label>
                                                <select class="form-select bg-light " name="gender" disabled>
                                                    <option value="1" {{ old('gender', $user->gender) == 1 ? 'selected' : '' }} >Nam</option>
                                                    <option value="0" {{ old('gender', $user->gender) == 0 ? 'selected' : '' }}>Nữ</option> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-6">
                                <div class="info-section mb-4">
                                    <h6 class="section-title bg-light p-2 mb-3">
                                        <i class="bi bi-telephone me-2"></i>Thông tin liên hệ
                                    </h6>
                                    <div>
                                            <input type="text" hidden value="{{ $user->email }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Email</label>
                                        
                                        <input type="email" class="form-control bg-light @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email', $user->email) }}" readonly >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
    
                                    <div class="form-group mb-3">
                                        <label>Xác thực email</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light" 
                                                   value="{{ $user->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}" disabled>
                                            @if(!$user->email_verified_at)
                                                <button type="button" class="btn btn-outline-secondary" 
                                                        data-bs-toggle="tooltip" title="Gửi lại email xác thực">
                                                    <i class="bi bi-send"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control bg-light @error('phone') is-invalid @enderror" 
                                               name="phone" value="{{ old('phone', $user->phone) }}" readonly>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
    
                                    <div class="form-group mb-3">
                                        <label class="form-label ">Địa chỉ</label>
                                        <textarea class="form-control bg-light" name="address" rows="2" readonly>{{ old('address', $user->address) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-12">
                                <div class="info-section mb-4">
                                    <h6 class="section-title bg-light p-2 mb-3">
                                        <i class="bi bi-shield-lock me-2"></i>Thông tin tài khoản
                                    </h6>
                                    
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Mật khẩu mới</label>
                                                <input type="text" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       name="password" 
                                                       placeholder="Để trống nếu không đổi mật khẩu"
                                                       autocomplete="new-password">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Chỉ nhập nếu muốn thay đổi mật khẩu</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Xác nhận mật khẩu</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="password_confirmation"
                                                       autocomplete="new-password">
                                            </div>
                                        </div>
                                        

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Vai trò</label>
                                                <select class="form-select" name="role_id" 
                                                    {{ auth()->user()->username !== 'admin' ? 'disabled' : '' }}>
                                                    <option value="0" {{ old('role_id', $user->role_id) == 0 ? 'selected' : '' }}>Người dùng</option>
                                                    <option value="1" {{ old('role_id', $user->role_id) == 1 ? 'selected' : '' }}>Quản trị viên</option>
                                                </select>
                                                
                                                @if(auth()->user()->username !== 'admin')
                                                    <small class="text-muted">Chỉ Admin gốc mới được thay đổi vai trò</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="action-buttons mt-4 d-flex justify-content-between border-top pt-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                                </a>
                                
                                <button type="reset" class="btn btn-warning">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Đặt lại
                                </button>
                                
                                <button type="button" class="btn btn-primary" onclick="confirmEdit()" >
                                    <i class="bi bi-save me-1"></i> Lưu thay đổi
                                </button>
                                
                                {{-- @if(
                                    (auth()->user()->username === 'admin' && $user->username !== 'admin') || 
                                    (auth()->user()->role_id == 1 && $user->role_id == 0)
                                )
                                    <a href="#" class="bi-trash-fill text-danger fs-4" title="Xóa người dùng"
                                    onclick="event.preventDefault(); openDeleteModal('{{ route('admin.user.destroy', $user->id) }}');">
                                 </a>
                                @endif --}}
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </section>
    </div>
    
    
    
    <script>
    function openDeleteModal(actionUrl) {
        document.getElementById('deleteForm').action = actionUrl;
    
        new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
    }
    function confirmEdit() {
        const form = document.getElementById('editForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const password = document.querySelector('input[name="password"]').value;
    const passwordConfirm = document.querySelector('input[name="password_confirmation"]').value;
    
    if (password !== '' || passwordConfirm !== '') {
        new bootstrap.Modal(document.getElementById('editConfirmationModal')).show();
    } else {
        form.submit();
    }
    }

    function submitEditForm() {
        document.getElementById('editForm').submit();
    }
    </script>
</div>
{{-- modal xác nhận sửa --}}
<div class="modal fade" id="editConfirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận thay đổi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn đang thay đổi thông tin người dùng. Thông báo sẽ được gửi đến email của họ.</p>
                <p><strong>Những thay đổi:</strong></p>
                <ul id="changesList">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="submitEditForm()">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#editConfirmationModal').on('show.bs.modal', function () {
            const changesList = document.getElementById('changesList');
            changesList.innerHTML = '';
                       
            const password = document.querySelector('input[name="password"]').value;
            if (password) {
                changesList.innerHTML += '<li>Thay đổi mật khẩu</li>';
            }
        });
    });
    </script>
{{-- modal xóa  --}}
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa người dùng này?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
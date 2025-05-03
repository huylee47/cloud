<?php

namespace App\Http\Controllers;

use App\Events\UserBlocked;
use App\Mail\ForgotPassword;
use App\Mail\verifyAccount;
use App\Mail\UserUpdatedMail;
use App\Models\Cart;
use Illuminate\Auth\Notifications\VerifyEmail;
use Mail;
use App\Models\User;
use App\Models\UserResetToken;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\alert;

class UserController extends Controller
{
    // ADMIN
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $account = User::where('username', $username)->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản.');
        }

        if (!Hash::check($password, $account->password)) {
            return redirect()->back()->with('error', 'Sai thông tin đăng nhập');
        }

        if ($account->role_id != 1 && $account->role_id != 2) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập vào trang quản trị.');
        }

        Auth::login($account);

        return redirect()->route('admin.index')->with('success', 'Đăng nhập thành công.');
    }

    public function index()
    {

        $loadAll = User::all();
        return view('admin.user.index', compact('loadAll'));
    }
    public function block(Request $request)
    {
        $user = User::find($request->id);
    // dd($user->id);
        if ($user) {
            $user->update(['status' => 0]);
    
            if ($user->role_id == 2) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
                
                Log::info("Phát sự kiện UserBlocked cho userId: {$user->id}");
                broadcast(new UserBlocked($user->id))->toOthers();

                
            }
        }
    
        return redirect()->route('admin.user.index')->with('success', 'Khóa tài khoản thành công.');
    }

    public function open(Request $request)
    {
        $user = User::find($request->id);
        $user->update(['status' => 1]);
        return redirect()->route('admin.user.index')->with('success', 'Mở tài khoản thành công.');
    }
    public function create_user()
    {
        return view('admin.user.create');
    }
    public function store_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:11|unique:users,phone',
            'password' => 'required|string|min:8',
            'confirm_Password' => 'required|string|same:password'
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.max' => 'Tên đăng nhập không được vượt quá 255 ký tự.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.required' => 'Vui lòng nhập email.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 11 ký tự.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'confirm_Password.required' => 'Vui lòng nhập mật khẩu xác nhận.',
            'confirm_Password.same' => 'Mật khẩu xác nhận phải trùng với mật khẩu đã nhập.'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 1,
            'role_id' => 1,
            'password' => Hash::make($request->password),
        ]);


        return redirect()->route('admin.user.index')->with('success', 'tạo tài khoản thành công.');
    }

    public function checkPhone(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            return response()->json([
                'status' => 'exists',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'address' => $user->address,
                ]
            ]);
        } else {
            return response()->json(['status' => 'not_found']);
        }
    }

    //client
    public function loginClient(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $account = User::where('username', $username)->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản.');
        }

        if (!Hash::check($password, $account->password)) {
            return redirect()->back()->with('error', 'Sai thông tin đăng nhập.');
        }

        if ($account->status != 1) {
            return redirect()->back()->with('error', 'Tài khoản của bạn đã bị khóa.');
        }

        if ($account->role_id != 1 && $account->email_verified_at == null) {
            return redirect()->back()->with('error', 'Vui lòng kiểm tra email và xác minh tài khoản');
        }
        Auth::login($account);

        $cartId = session()->get('cart_id');
        $hasSessionCart = false;

        if ($cartId) {
            $sessionCartItems = Cart::where('cart_id', $cartId)->get();

            if ($sessionCartItems->count() > 0) {
                $hasSessionCart = true;

                foreach ($sessionCartItems as $item) {
                    $existingItem = Cart::where('user_id', $account->id)
                        ->where('variant_id', $item->variant_id)
                        ->first();

                    if ($existingItem) {
                        $existingItem->quantity += $item->quantity;
                        $existingItem->save();
                    } else {
                        $item->user_id = $account->id;
                        $item->cart_id = null;
                        $item->save();
                    }
                }

                session()->forget('cart_id');
            }
        }

        if ($hasSessionCart) {
            return redirect()->route('client.cart.index')->with('success', 'Đăng nhập thành công, vui lòng kiểm tra giỏ hàng của bạn.');
        }

        return redirect()->route('home')->with('success', 'Đăng nhập thành công.');
    }
    public function create()
    {
        return view('client.login.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:11|unique:users,phone',
            'password' => 'required|string|min:8',
            'password-confirm' => 'required|string|same:password'
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.max' => 'Tên đăng nhập không được vượt quá 255 ký tự.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.required' => 'Vui lòng nhập email.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 11 ký tự.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password-confirm.required' => 'Vui lòng nhập mật khẩu xác nhận.',
            'password-confirm.same' => 'Mật khẩu xác nhận phải trùng với mật khẩu đã nhập.'
        ]);

        $data = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 1,
            'role_id' => 0,
            'password' => Hash::make($request->password),
        ]);

        if ($acc = $data) {
            Mail::to($acc->email)->send(new verifyAccount($acc));
            return redirect()->route('login.client')->with('success', 'Đăng ký thành công, vui lòng check gmail của bạn');
        }
        return redirect()->back()->with('no', 'tạo không thành công vui lòng kiểm tra lại');
    }

    public function veryfi($email)
    {
        User::where('email', $email)->whereNull('email_verified_at')->firstOrFail();
        User::where('email', $email)->update(['email_verified_at' => date('Y-m-d')]);
        return redirect()->route('login.client')->with('success', 'xác minh thành công');
    }

    public function forgot_password()
    {
        return view('client.login.forgot');
    }
    public function check_forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'email.exists' => 'Email không tồn tại.'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email không tồn tại.');
        }

        $token = \Str::random(20);
        $tokenData = [
            'email' => $request->email,
            'token' => $token
        ];

        if (UserResetToken::create($tokenData)) {
            Mail::to($request->email)->send(new ForgotPassword($user, $token));
            return redirect()->back()->with('success', 'Vui lòng kiểm tra gmail của bạn');
        }

        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi tạo token đặt lại mật khẩu.');
    }


    public function reset_password($token, Request $request)
    {
        UserResetToken::where('token', $token)->firstOrFail();
        return view('client.login.reset_password', ['token' => $token]);
    }
    public function check_reset_password($token, Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8',
            'password-confirm' => 'required|string|same:password'
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password-confirm.required' => 'Vui lòng nhập mật khẩu xác nhận.',
            'password-confirm.same' => 'Mật khẩu xác nhận phải trùng với mật khẩu đã nhập.'
        ]);

        $tokenRecord = UserResetToken::where('token', $token)->firstOrFail();
        $user = $tokenRecord->user;
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return redirect()->route('login.client')->with('success', 'Đổi mật khẩu thành công');
        }

        return redirect()->back()->with('no', 'Đổi mật khẩu không thành công');
    }


    public function edit(Request $request)
    {
        $user = Auth::user();
        return view('client.user.edit', compact('user'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',
        ]);
        Auth::user();
        User::find($request->id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);
        return redirect()->route('client.edit')->with('success', 'Sửa thành công');
    }
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        return view('client.user.changePassword', compact('user'));
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'passwordOld' => 'required|string',
            'password' => 'required|string|min:8|unique:users',
            'password-confirm' => 'required|string|same:password'
        ], [
            'passwordOld.required' => 'Vui lòng nhập mật khẩu cũ.',
            'password.unique' => 'Mật khẩu mới giống mật khẩu cũ.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password-confirm.required' => 'Vui lòng nhập mật khẩu xác nhận.',
            'password-confirm.same' => 'Mật khẩu xác nhận phải trùng với mật khẩu đã nhập.'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->passwordOld, $user->password)) {
            return redirect()->back()->with('error', 'Mật khẩu cũ không đúng.');
        }

        if (Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Mật khẩu mới trùng với mật khẩu cũ.');
        }

        User::find($request->id)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('client.changePassword')->with('success', 'Đổi mật khẩu thành công');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    //edit phía admin
    public function editUser($id)
{
    $user = User::findOrFail($id);
    if (auth()->user()->username !== 'admin' && $user->role_id == 1) {
        abort(403, 'Bạn không có quyền chỉnh sửa admin khác');
    }
    
    return view('admin.user.edit', compact('user'));
}

// public function updateUser(Request $request, $id)
// {
//     $user = User::findOrFail($id);
    
//     if (auth()->user()->username !== 'admin' && $user->role_id == 1) {
//         abort(403, 'Bạn không có quyền cập nhật admin khác');
//     }

//     $request->validate([
//         'name' => 'required|string|max:50',
//         'email' => 'required|email|unique:users,email,'.$id,
//         'phone' => 'required|string|min:10|max:10|regex:/^[0-9]+$/',
//         'dob' => 'required|date|before_or_equal:today|after:1950-01-01',
//         'address' => 'required|string|max:500',
//         'password' => 'nullable|min:8|confirmed'

//     ], [
//         'name.required' => 'Vui lòng nhập tên người dùng',
//         'name.max' => 'Tên không được vượt quá 50 ký tự',
        
//         'email.required' => 'Vui lòng nhập email',
//         'email.email' => 'Email không hợp lệ',
//         'email.unique' => 'Email này đã được sử dụng',
        
//         'phone.required' => 'Vui lòng nhập số điện thoại',
//         'phone.min' => 'Số điện thoại ít nhất 10 ký tự',
//         'phone.max' => 'Số điện thoại tối đa 10 ký tự',
//         'phone.regex' => 'Số điện thoại chỉ được chứa số',
        
//         'dob.required' => 'Vui lòng nhập ngày sinh',
//         'dob.date' => 'Ngày sinh không hợp lệ',
//         'dob.before_or_equal' => 'Ngày sinh không thể ở tương lai',
//         'dob.after' => 'Ngày sinh phải sau năm 1950',
        
//         'address.required' => 'Vui lòng nhập địa chỉ',
//         'address.max' => 'Địa chỉ không được vượt quá 250 ký tự'
//     ]);

//     try {
//         $user->update([
//             'name' => $request->name,
//             'email' => $request->email,
//             'phone' => $request->phone,
//             'role_id' => $request->role_id,
//             'dob' => $request->dob,
//             'address' => $request->address
//         ]);
        
//         return redirect()->route('admin.user.index')
//                        ->with('success', 'Cập nhật thông tin thành công');
        
//     } catch (\Exception $e) {
//         Log::error('Lỗi cập nhật người dùng: '.$e->getMessage());
//         return back()->with('error', 'Cập nhật thất bại! Vui lòng thử lại');
//     }
// }
public function updateUser(Request $request, $id)
    {

        $user = User::findOrFail($id);
        if (auth()->user()->username !== 'admin' && $user->role_id == 1) {
            abort(403, 'Bạn không có quyền sửa admin khác');
        }
        $rules = [
            'password' => 'nullable|min:8|confirmed', 
        ];

        $messages = [
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ];

        $request->validate($rules, $messages);

        try {
            $data = [
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
                $changes['password'] = $request->password;
            }

            $user->update($data);

            if (!empty($changes)) {
                $mailData = [
                    'user' => $user,
                    'changes' => $changes,
                    'username' => $user->username 
                ];
                Mail::to($user->email)->send(new UserUpdatedMail(
                    $user,           
                    $user->username, 
                    $changes         
                ));
            }
            
            return redirect()->route('admin.user.index')->with('success', 'Cập nhật thành công!');

        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật user: ' . $e->getMessage());
            return back()->with('error', 'Cập nhật thất bại! Vui lòng thử lại.');
        }
    }
public function destroy($id)
{
    $userToDelete = User::findOrFail($id);
    $currentUser = auth()->user();

    if ($userToDelete->id === $currentUser->id) {
        return redirect()->route('admin.user.index')->with('error', 'Bạn không thể xóa chính mình');
    }

    if ($currentUser->username === 'admin') {
        if ($userToDelete->username === 'admin') {
            return redirect()->route('admin.user.index')->with('error', 'Không thể xóa admin chính');
        }
        $userToDelete->delete();
        return redirect()->route('admin.user.index')->with('success', 'Đã xóa tài khoản thành công');
    }

    if ($currentUser->role_id == 1 && $userToDelete->role_id == 0) {
        $userToDelete->delete();
        return redirect()->route('admin.user.index')->with('success', 'Đã xóa user thường thành công');
    }

}
}

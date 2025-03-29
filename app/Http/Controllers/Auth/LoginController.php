<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserStoreRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Models\UserVerify;
use App\Traits\Loggable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use mysql_xdevapi\Collection;
use phpseclib3\Crypt\Hash;
use function Laravel\Prompts\password;

class LoginController extends Controller
{
    use Loggable;
    public function showLogin(){
        return view('front.auth.login');
    }
    public function showLoginUser(){
        return view('front.auth.login');
    }
    public function showRegister(){
        return view('front.auth.register');
    }
    public function login(LoginRequest $request){
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember;

        !is_null($remember) ? $remember = true : $remember = false;

        $user = User::where('email', $email)->first();

        if ($user && \Hash::check($password, $user->password)){
            Auth::login($user, $remember);

            $this->log('login',$user->id,$user,User::class);

            //Auth::loginUsingId($user->id);
            $userIsAdmin = Auth::user()->is_admin;
            if(!$userIsAdmin){
                return redirect()->route('home');
            }
            return redirect()->route('admin.index');
        }else{
            return redirect(route('front.auth.login'))->withErrors([
                'email' => 'Girilen bilgiler yanlış',
            ])
                ->onlyInput('email','remember');

        }
    }
    public function login2(LoginRequest $request){
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember;

        !is_null($remember) ? $remember = true : $remember = false;

        $user = User::where('email', $email)->first();

        if ($user && \Hash::check($password, $user->password)){
            Auth::login($user, $remember);
            //Auth::loginUsingId($user->id);
            return redirect()->route('categories.index');
        }else{
            return redirect(route('front.auth.login'))->withErrors([
                'email' => 'Girilen bilgiler yanlış',
            ])
                ->onlyInput('email','remember');
        }
    }
    public function login3(LoginRequest $request){
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember;

        !is_null($remember) ? $remember = true : $remember = false;

        $user = User::where('email', $email)->first();

        if ($user && \Hash::check($password, $user->password)){
            Auth::login($user, $remember);
            //Auth::loginUsingId($user->id);
            return redirect()->route('categories.index');
        }else{
            return redirect(route('login'))->withErrors([
                'email' => 'Girilen bilgiler yanlış',
            ])
                ->onlyInput('email','remember');
        }
    }
    public function logout(Request $request){
        if (Auth::check()){
            $isAdmin = Auth::user()->is_admin;

            $this->log('logout', \auth()->id(), \auth()->user()->toArray(),User::class);

            Auth::logout();

            $request->session()->invalidate();
            //tokeni tekrardan oluşturulsun diye
            $request->session()->regenerateToken();
            if (!$isAdmin)
            {
                return redirect()->route('home');
            }

            return redirect(route('front.auth.login'));
        }
    }
    public function register(UserStoreRequest $request){
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->status = 0;
        $user->save();

        event(new UserRegistered($user));

//        $token = Str::random(60);
//        UserVerify::create([
//            'user_id' => $user->id,
//            'token' => $token,
//        ]);
//
//        Mail::send('email.verify', compact('token'), function ($mail) use ($user) {
//            $mail->to($user->email);
//            $mail->subject('Doğrulama Emaili');
////            $mail->from('');
//        });

        alert()
            ->success('Başarılı', 'Mailinizi onaylamanız için onay maili gönderilmiştir. Lütfen mail kutunuzu kontrol ediniz.')
            ->showConfirmButton('Tamam', '#3085d6')
            ->autoclose(5000);

        return redirect()->back();
    }
    public function verify(Request $request, $token){
        $verifyQuery = UserVerify::query()->with('user')->where('token', $token);
        $find = $verifyQuery->first();

        if (!is_null($find)){
            $user = $find->user;

            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
                $user->status = 1;
                $user->save();

                $this->log('verify user', $user->id, $user->toArray(),User::class);

                $verifyQuery->delete();
                $message = 'Emailiniz Doğrulandı ';
            }else
            {
                $message = 'Emailiniz daha önceden doğrulandı giriş yapabilirsiniz.';
            }
            alert()
                ->success('Başarılı', $message)
                ->showConfirmButton('Tamam', '#3085d6')
                ->autoclose(5000);
//            return view('auth.login', compact('verifyQuery', 'user', 'token', 'message'));
            return redirect()->route('front.auth.login');
        }else{
            abort(404);
        }
    }
    public function socialLogin($driver)
    {
        return Socialite::driver($driver)->redirect();
    }
    public function socialVerify($driver)
    {
        $user = Socialite::driver($driver)->user();
        $userCheck = User::where('email', $user->getEmail())->first();
        if (!is_null($userCheck)){
            Auth::login($userCheck);
            $this->log('verify user', \auth()->id(), \auth()->user()->toArray(),User::class);
            return redirect()->route('home');
        }

        $username = Str::slug($user->getName());

        $userCreate = User::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => bcrypt(''),
            'username' => is_null($this->checkUsername($username)) ? $username : $username.uniqid(),
            'status' => 1,
            'email_verified_at' => now(),
            $driver.'_id' => $user->getId(),
        ]);

        Auth::login($userCreate);
        return redirect()->route('home');
    }
    public function checkUserName(string $userName): null|object
    {
        return User::query()->where('username', $userName)->first();
    }
    public function showPasswordReset()
    {
        return view('front.auth.reset-password');
    }
    public function sendPasswordReset(Request $request){
        $email = $request->email;
        $find = User::where('email', $email)->firstOrFail();

        $tokenFind = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!is_null($tokenFind))
        {
            $token = $tokenFind->token;
        } else{
            $token = Str::random(60);

            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]);
        }

    if ($tokenFind && now()->diffInHours($tokenFind->created_at) < 5){
            alert()
                ->warning('Hata', 'Şifrenizi sıfırlamak için zaten bir mail gönderilmiştir. Lütfen mail kutunuzu kontrol ediniz.')
                ->showConfirmButton('Tamam', '#3085d6')
                ->autoclose(5000);

            return redirect()->back();
        }
        //$find ı belki kullanıcıya ihtiyacımız olur diye ekledik
        Mail::to($find->email)->send(new ResetPasswordMail($find, $token));

        $this->log('password reset mail send', $find->id, $find->toArray(),User::class,true);


        alert()
            ->success('Başarılı', 'Şifrenizi sıfırlamak için mail gönderilmiştir. Lütfen mail kutunuzu kontrol ediniz.')
            ->showConfirmButton('Tamam', '#3085d6')
            ->autoclose(5000);

        return redirect()->back();
    }
    public function showPasswordResetConfirm(Request $request){
        $token = $request->token;

        $tokenExist = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!($tokenExist)){
            abort(404);
        }

        return view('front.auth.reset-password', compact('token'));
    }
    public function passwordReset(PasswordResetRequest $request){
        $tokenQuery = DB::table('password_reset_tokens')->where('token', $request->token);
        $tokenExist = $tokenQuery->first();
        if (!($tokenExist)){
            abort(404);
        }
        $userExist = User::where('email', $tokenExist->email)->first();

        if (!($userExist)){
            abort(400, 'Lütfen yöneticiyle iletişime geçin');
        }
        $userExist->update([
            'password' => bcrypt($request->password),
        ]);

        $tokenQuery->delete();

        alert()
            ->success('Başarılı', 'Parolanız Sıfırlanmıştır giriş yapabilirsiniz')
            ->showConfirmButton('Tamam', '#3085d6')
            ->autoclose(5000);

        return redirect()->route('user.login');
    }

}

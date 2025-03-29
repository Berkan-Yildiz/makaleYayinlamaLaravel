<h1>Doğrulama Emaili</h1>
<p>
    Merhaba {{ $user->name }}, hoşgeldiniz.
</p>
<p>
    Lütfen aşağıdaki linkten e-posta adresinizi doğrulayınız.
</p>
<br>
<a href="{{ route('verify-token', ['token' => $token] ) }}">Mailimi Doğrula</a>

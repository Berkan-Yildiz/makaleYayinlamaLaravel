@extends("layouts.front")

@section("css")
@endsection

@section("content")
    <div class="row">
        <div class="col-md-12">
            <x-bootstrap.card>
                <x-slot:header>
                    Kayıt Ol
                </x-slot:header>
                <x-slot:body>
                    <form action="{{ route('register') }}" method="POST" name="register-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <input type="text" name="name" id="name" class="form-control" placeholder="İsim">
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="text" name="username" id="username" class="form-control" placeholder="Kullanıcı Adı">
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Şifre">
                                <small>
                                    Parolanız küçük harf, büyük harf, rakam ve özel karakter içermelidir.
                                </small>
                                <hr class="my-4">
                            </div>
                            <div class="col-md-12 social-media-register">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('socialLogin', ['driver' => 'google']) }}">
                                        <i class="fa fa-google fa-2x me-4"></i>
                                    </a>
                                    <a href="">
                                        <i class="fa fa-facebook fa-2x me-4"></i>
                                    </a>
                                    <a href="">
                                        <i class="fa fa-github fa-2x me-4"></i>
                                    </a>
                                </div>
                                <hr class="my-4">

                            </div>

                            <div class="col-md-12">
                                <button class="btn btn-success w-100">
                                    Kayıt Ol
                                </button>
                            </div>
                        </div>
                    </form>
                </x-slot:body>
            </x-bootstrap.card>
        </div>
    </div>
@endsection

@section("js")
@endsection

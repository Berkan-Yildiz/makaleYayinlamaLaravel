@extends("layouts.admin")

@section("css")
@endsection

@section("content")
    @php
    if (isset($theme))
    {
        $name = $theme->name;
        $themeType = $theme->getRawOriginal('themeType');
        $process = $theme->getRawOriginal('process');
        $status = $theme->status;
        $body = json_decode($theme->body);

        $custom = false;
        $logo = '';
        $logo_alt = '';
        $logo_title = '';

        $reset_password_image = '';
        $reset_password_image_alt = '';
        $reset_password_image_title = '';

        $title = '';
        $description = '';
        $button_text = '';

        if ($themeType == 1)
        {
            $custom = true;
        }else if ($themeType == 2){
            $logo = $body->logo;
            $logo_alt = $body->logo_alt;
            $logo_title = $body->logo_title;

            $reset_password_image = $body->reset_password_image;
            $reset_password_image_alt = $body->reset_password_image_alt;
            $reset_password_image_title = $body->reset_password_image_title;

            $title = $body->title;
            $description = $body->description;
            $button_text = $body->button_text;
        }
    }else{
        $theme = null;
    }
    @endphp
    <x-bootstrap.card>
        <x-slot:header>
            <h2 class="">Tema {{ isset($theme) ? "Güncelleme" : "Ekleme" }}</h2>
        </x-slot:header>

        <x-slot:body>
            <form action="{{ $theme ? route('admin.email-themes.edit') : route('admin.email-themes.create') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($theme)
                    <input type="hidden" name="id" value="{{ $theme->id }}">
                @endif

                <div class="theme-select">
                    <div class="row">
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif
                        <div class="col-md-4">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Tema Adı" value="{{ $theme ? $name : '' }}">
                        </div>
                        <div class="col-md-4">
                            <select name="themeType" id="theme-type" class="form-control" {{ $theme ? 'disabled' : '' }}>
                                <option value="">Tema Türü Seçiniz</option>
                                <option value="1" {{ $theme && $themeType == 1 ? 'selected' : '' }}>Kendim içerik oluşturmak istiyorum</option>
                                <option value="2" {{ $theme && $themeType == 2 ? 'selected' : '' }}>Parola Sıfırlama Maili</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="process" id="process" class="form-control" {{ $theme ? 'disabled' : '' }}>
                                <option value="">İşlem Seçiniz</option>
                                <option value="1" {{ $theme && $process == 1 ? 'selected' : '' }}>Email Doğrulama Maili İçeriği</option>
                                <option value="2" {{ $theme && $process == 2 ? 'selected' : '' }}>Parola Sıfırlama Maili İçeriği</option>
                                <option value="3" {{ $theme && $process == 3 ? 'selected' : '' }}>Parola Sıfırlama İşlemi Tamamlandığında Maili İçeriği</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="content mt-4">
                    <div class="custom-content {{ $theme && $custom ? '' : 'd-none' }}">
                        <div class="row">
                            <div class="col-12 mt-2">
                                <h3>Kendi İçeriğinizi Oluşturabilirsiniz</h3>
                                <hr>
                                <h5>Kullanabileceğinzi Alanlar</h5>
                                <p>
                                    {link}, {username}, {useremail}
                                </p>
                            </div>

                            <div class="col-12 mt-2">
                                <textarea class="form-control" name="custom_content" id="custom_content" cols="30" rows="5">{!! $theme && $themeType == 1 ? $body : '' !!}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="password-reset-mail {{ $theme && !$custom ? '' : 'd-none' }}">
                        <div class="row">
                            <div class="col-12 mt-2">
                                <h3>Parola Sıfırlama Maili Alanlarını Doldurabilirsiniz</h3>
                                <hr>
                            </div>
                            <div class="col-6 mt-4">
                                <a href="javascript:void(0)" class="btn btn-warning btn-sm w-100" id="btnAddLogoImage" data-input="logo" data-preview="imgLogo">
                                    Logo Görseli
                                </a>
                                <input type="hidden" name="passwordResetMail[logo]" id="logo" value="{{ $theme ? $logo : '' }}">
                            </div>
                            <div class="col-6 mt-4" id="imgLogo">
                                <img src="{{ $theme ? $logo : '' }}" height="65" alt="" id="imgLogo2">
                            </div>
                            <div class="col-6 mt-4">
                                <input type="text" name="passwordResetMail[logo_alt]" id="logo_alt" class="form-control" placeholder="Logo Alt Attribute" value="{{ $theme ? $logo_alt : '' }}">
                            </div>
                            <div class="col-6 mt-4">
                                <input type="text" name="passwordResetMail[logo_title]" id="logo_title" class="form-control" placeholder="Logo Title Attribute" value="{{ $theme ? $logo_title : '' }}">
                            </div>

                            <div class="col-6 mt-4">
                                <a href="javascript:void(0)" class="btn btn-warning btn-sm w-100"
                                   id="btnAddResetPasswordImage"
                                   data-input="resetPasswordImage"
                                   data-preview="resetPassword">
                                    Reset Password Görseli
                                </a>
                                <input type="hidden" name="passwordResetMail[reset_password_image]" id="resetPasswordImage" value="{{ $theme ? $reset_password_image : '' }}">
                            </div>
                            <div class="col-6 mt-4" id="resetPassword">
                                <img src="{{ $theme ? $reset_password_image : '' }}" height="65" alt="" id="passwordResetMail[imgResetPassword]">
                            </div>
                            <div class="col-6 mt-4">
                                <input type="text" name="passwordResetMail[reset_password_image_alt]" id="reset_password_image_alt" class="form-control" placeholder="Reset Password Alt Attribute" value="{{ $theme ? $reset_password_image_alt : '' }}">
                            </div>
                            <div class="col-6 mt-4">
                                <input type="text" name="passwordResetMail[reset_password_image_title]" id="reset_password_image_title" class="form-control" placeholder="Reset Password Title Attribute" value="{{ $theme ? $reset_password_image_title : '' }}">
                            </div>

                            <div class="col-6 mt-4">
                                <input type="text" name="passwordResetMail[title]" id="title" class="form-control" placeholder="Başlık Alanı" value="{{ $theme ? $title : '' }}">
                            </div>
                            <div class="col-6 mt-4">
                                <input type="text" name="passwordResetMail[description]" id="description" class="form-control" placeholder="İçerik Alanı" value="{{ $theme ? $description : '' }}">
                            </div>
                            <div class="col-6 mt-4">
                                <input type="text" name="passwordResetMail[button_text]" id="buttonText" class="form-control" placeholder="Buton İçeriği Alanı" value="{{ $theme ? $button_text : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 themeStatus mt-4 {{ $theme ? '' : 'd-none' }}">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ isset($theme) && $status  ? "checked" : "" }}>
                                <label class="form-check-label" for="status">
                                    Tema aktif olarak görünsün mü?
                                </label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <hr>
                            <button class="btn btn-success w-50 ">Kaydet</button>
                        </div>
                    </div>
                </div>
            </form>

        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section("js")
    <script src="{{ asset("assets/plugins/flatpickr/flatpickr.js") }}"></script>
    <script src="{{ asset("assets/js/pages/datepickers.js") }}"></script>
    <script src="{{ asset("vendor/laravel-filemanager/js/stand-alone-button.js") }}"></script>

    <script>
        $('#theme-type').change(function (){
            let val = $(this).val();

            switch (val){
                case "1":
                    $('.custom-content').removeClass('d-none');
                    $('.themeStatus').removeClass('d-none');
                    $('.password-reset-mail').addClass('d-none');

                    // ilk kutu değiştiğinde sağdaki seçim nulla dönsün diye
                    $('#process').val('').change;
                    $('#process').removeAttr('style');
                    break;
                case '2':
                    $('.password-reset-mail').removeClass('d-none');
                    $('.themeStatus').removeClass('d-none');
                    $('.custom-content').addClass('d-none');

                    // Sadece Parola Sıfırlama Maili İçeriği seçili olabilecek
                    $('#process').val('2').change;
                    $('#process').val('2').attr('style', 'pointer-events: none');


                    break;
                default:
                    $('.password-reset-mail').addClass('d-none');
                    $('.custom-content').addClass('d-none');
                    $('.themeStatus').addClass('d-none');
                    $('#process').val('').change;
                    $('#process').removeAttr('style');
                    break;
            }

        })

        $('#btnAddLogoImage').filemanager();
        $('#btnAddResetPasswordImage').filemanager();

    </script>

@endsection


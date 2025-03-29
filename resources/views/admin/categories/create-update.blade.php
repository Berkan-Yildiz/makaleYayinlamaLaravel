@extends("layouts.admin")
@section("title")
    Kategori {{ isset($category) ? "Güncelleme" : "Ekleme" }}
@endsection
@section("css")
@endsection

@section("content")
<x-bootstrap.card>
    <x-slot name="header">
        <h1 class="card-title">
            Kategori {{ isset($category) ? "Güncelleme" : "Ekleme" }}
        </h1>
    </x-slot>
    <x-slot name="body">
        <div class="card">
            <div class="card-body">
                <p class="card-description"> </p>
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                        <form action="{{ isset($category) ? route('categories.edit', ['id' => $category->id]) : route('categories.create') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              id="categoryForm"
                        >
                        @csrf
                            <label for="color">
                                Kategorinin Rengi
                            </label>
                            <input type="color" name="color" id="color" value=" {{ isset($category) ? ($category->color) : '' }}">
                        <input type="text"
                               class="form-control form-control-material m-b-sm"
                               aria-describedby="solidBorderedInputExample"
                               placeholder="Kategori Adı"
                               name="name"
                               id="name"
                               value="{{ isset($category) ? $category->name : '' }}"

                        >

                        <input type="text"
                               class="form-control form-control-material m-b-sm"
                               aria-describedby="solidBorderedInputExample"
                               placeholder="Kategori Slug"
                               value="{{ isset($category) ? $category->slug : '' }}"
                               name="slug"
                        >
                        <textarea
                            class="form-control form-control-material m-b-sm"
                            name="description"
                            id="description"
                            cols="30"
                            rows="5"
                            placeholder="Kategori Açıklama"
                            style="resize: none"
                        >{{ isset($category) ? $category->description : '' }}</textarea>
                        <input type="number"
                               class="form-control form-control-material m-b-sm"
                               aria-describedby="solidBorderedInputExample"
                               placeholder="Sıralama"
                               value="{{ isset($category) ? $category->order : '' }}"
                               name="order"
                        >
                        <select class="form-select form-control-material m-b-sm" aria-label="Üst Kategori Seçimi">
                            <option value="{{ null }}">Üst Kategori Seçimi</option>
                            @foreach($categories as $item)
                                <option value="{{ $item->id }} "{{ isset($category) && $category->id == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        <textarea
                            class="form-control form-control-material m-b-sm"
                            name="seo_keywords"
                            id="seo_keywords"
                            cols="30"
                            rows="5"
                            placeholder="Seo Keywords"
                            style="resize: none"
                        >{{ isset($category) ? $category->seo_keywords : '' }}</textarea>
                        <textarea
                            class="form-control form-control-material m-b-sm"
                            name="seo_description"
                            id="seo_description"
                            cols="30"
                            rows="5"
                            placeholder="Seo Description"
                            style="resize: none"
                        >{{ isset($category) ? $category->seo_description : '' }}</textarea>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ isset($category) && $category->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                Kategori Sitede Görünsün mü?
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="feature_status" value="1" id="feature_status" {{ isset($category) && $category->feature_status ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_status">
                                Kategori Anasayfada Öne Çıkartılsın mı?
                            </label>
                        </div>
                        <hr>
                        <div class="col-6 mx-auto mt-5">
                            <button type="button" class="btn btn-success btn-default btn-rounded w-100" id="btnSave">
                                {{ isset($category) ? "Güncelle" : "Kaydet" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>
</x-bootstrap.card>




@endsection
@section("js")
    <script>
        let name = $('#name');
        let parent_id = $('#parent_id');

        $(document).ready(function (){

            $('#btnSave').click(function () {
                if (name.val().trim() === "" || name.val().trim() == null){
                    Swal.fire({
                        title:'Uyarı',
                        text:'Lütfen Kategori alanını doldurunuz',
                        confirmButtonText:'Tamam',
                        icon:'info'
                    });
                } else{
                    $('#categoryForm').submit();
                }
            })
        });
    </script>
@endsection

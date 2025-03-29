@extends("layouts.admin")

@section("content")


    @foreach ($categories as $category)
        <h5>
            {{ $category->id }}
            {{ $category->name }}
        </h5>
        <div>
            <td>
                <a href="javascript:void(0)" class="btn btn-success btnChangeStatus">Aktif</a>
            </td>
            <td>
                <a href="javascript:void(0)" class="btn btn-danger btnChangeStatus">Pasif</a>
            </td>

        </div>

        <div class="d-flex">
            <td>
                <a href="{{ route('categories.edit', ['id' => $category->id]) }}" class="btn btn-success btnChangeFeatureStatus">Edit</a>
            </td>
            <td>
                <a href="javascript:void(0)" class="btn btn-danger btnChangeFeatureStatus">Delete</a>
            </td>
        </div>
        <hr>
        <form action="" method="POST" id="statusChangeForm">
            @csrf
            <input type="hidden" name="id" id="inputStatus" value="">
        </form>
    @endforeach
    {{ $categories->links() }}

@endsection
@section("js")
    <script>
        $(document).ready(function (){
            $('.btnChangeFeatureStatus').click(function () {
                let categoryID = $(this).data('id');
                $('#inputStatus').val(categoryID);

                Swal.fire({
                    title: 'Feature Status değiştirmek istediğinize emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $('#statusChangeForm').attr("action", "{{ route('categories.changeFeatureStatus') }}");
                        $('#statusChangeForm').submit();
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            title: "Bilgi",
                            text: "Herhangi bir işlem yapılmadı",
                            confirmButtonText: 'Tamam',
                            icon: "info"
                        });
                    }
                })

            });

            $('.btnChangeFeatureStatus').click(function (){
              let categoryID = $(this).data('id');
              $('#inputStatus').val(categoryID);

               Swal.fire({
                   title: "Status değiştirilsin mi?",
                   showDenyButton: true,
                   showCancelButton: true,
                   confirmButtonText: "Evet",
                   denyButtonText: `Hayır`,
                   cancelButtonText: 'iptal'
               }).then((result) => {
                   /* Read more about isConfirmed, isDenied below */
                   if (result.isConfirmed) {
                       $('#statusChangeForm').attr("action","{{ route('categories.changeFeatureStatus') }}")
                       $('#statusChangeForm').submit();

                   } else if (result.isDenied) {
                       Swal.fire("Değişiklikler kayıt edilmedi", "", "info");
                   }
               });

           });

        });
    </script>
@endsection

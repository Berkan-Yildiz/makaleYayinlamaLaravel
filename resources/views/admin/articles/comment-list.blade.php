@extends("layouts.admin")
@section("title")
    @if($page == "commentList")
        Yorum Listesi
    @else
        Onay Bekleyen Yorum Listesi
    @endif
@endsection
@section("css")
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/plugins/flatpickr/flatpickr.min.css") }}">

    <style>
        .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: transparent;
            background: #363638;
            color: #fff;
        }
    </style>

@endsection

@section("content")
    <x-bootstrap.card>
        <x-slot:header>
            <h2>
                @if($page == "commentList")
                    Yorum Listesi
                @else
                    Onay Bekleyen Yorum Listesi
                @endif
            </h2>
        </x-slot:header>

        <x-slot:body>
            <form action="{{ $page == "commentList" ? route("articles.comment.list") : route("articles.pending-approval") }}" method="GET" id="formFilter">
                <div class="row">

                    <div class="col-3 my-2">
                        <select class="form-select" name="user_id">
                            <option value="{{ null }}">Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->get("user_id") == $user->id ? "selected" : "" }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($page == "commentList")
                        <div class="col-3 my-2">
                            <select class="form-select" name="status" aria-label="Status">
                                <option value="{{ null }}">Status</option>
                                <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Pasif</option>
                                <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Aktif</option>
                            </select>
                        </div>
                    @endif

                    <div class="col-3 my-2">
                        <input class="form-control flatpickr2 m-b-sm"
                               id="created_at"
                               name="created_at"
                               type="text"
                               value="{{ request()->get("created_at") }}"
                               placeholder="Yorum Tarihi">
                    </div>
                    <div class="col-3 my-2">
                        <input type="text" class="form-control" placeholder="Comment, Name, Email" name="search_text" value="{{ request()->get("search_text") }}">
                    </div>
                    <hr>
                    <div class="col-6 mb-2 d-flex mx-auto">
                        <button class="btn btn-primary w-50 me-2" type="submit">Filtrele</button>
                        <button class="btn btn-danger w-50" type="button" id="btnClearFilter">Filtreyi Temizle</button>
                    </div>
                    <hr>
                </div>

            </form>
            <x-bootstrap.table
                :class="'table-striped table-hover table-responsive'"
                :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">Makale Link</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">IP</th>
                    @if(isset($page) && $page =='commentList')
                        <th scope="col">Status</th>
                    @else
                        <th scope="col">Approve Status</th>
                    @endif
                    <th scope="col">Comment</th>
                    <th scope="col">Crated Date</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>

                <x-slot:rows>
                    @foreach($comments as $comment)
                        <tr id="row-{{ $comment->id }}">
                            <td>
                                <a href="{{ route("front.articleDetail", [
                                'user' => $comment->article->user->username,
                                'article' => $comment->article->slug
                                ]) }}" target="_blank">
                                    <span class="material-icons-outlined">visibility</span>
                                </a>
                            </td>

                            <td>{{ $comment->user?->name }}</td>
                            <td>{{ $comment->name }}</td>
                            <td>{{ $comment->email }}</td>
                            <td>{{ $comment->ip }}</td>
                            <td>
                                @if(isset($page))
                                    @if($comment->approve_status)
                                        <a href="javascript:void(0)" class="btn btn-success btn-sm btnChangeStatus" data-id="{{ $comment->id }}">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btnChangeStatus" data-id="{{ $comment->id }}">Pasif</a>
                                    @endif
                                @else
                                    @if($comment->status)
                                        <a href="javascript:void(0)" class="btn btn-success btn-sm btnChangeStatus" data-id="{{ $comment->id }}">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btnChangeStatus" data-id="{{ $comment->id }}">Pasif</a>
                                    @endif
                                @endif


                            </td>
                            <td>
                                <button type="button" class="btn btn-primary lookComment btn-sm p-0 px-2"
                                        data-comment="{{ $comment->comment }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                    <span class="material-icons-outlined" style="line-height: unset; font-size: 20px">visibility</span>
                                </button>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($comment->created_at)->translatedFormat("d F Y H:i:s") }}</td>
                            <td>
                                <div class="d-flex actions-{{ $comment->id }}">
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-name="{{ $comment->id }}"
                                       data-id="{{ $comment->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                    @if($comment->deleted_at)
                                        <a href="javascript:void(0)"
                                           class="btn btn-primary btn-sm btnRestore"
                                           data-name="{{ $comment->id }}"
                                           data-id="{{ $comment->id }}"
                                           title="Geri Al"
                                        >
                                            <i class="material-icons ms-0">undo</i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center">
                {{ $comments->appends(request()->all())->onEachside(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yorum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("js")
    <script src="{{ asset("assets/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/select2.js") }}"></script>
    <script src="{{ asset("assets/plugins/flatpickr/flatpickr.js") }}"></script>
    <script src="{{ asset("assets/js/pages/datepickers.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/popper.min.js") }}"></script>
    <script>
        $(document).ready(function ()
        {
            @if(isset($page) && $page!='commentList')
            $('.btnChangeStatus').click(function () {
                let id = $(this).data('id');
                let self = $(this);
                Swal.fire({
                    title: 'Onaylamak istediğinize emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('articles.pending-approval.changeStatus') }}",
                            data: {
                                id : id,
                                page: "{{ $page }}"
                            },
                            async:false,
                            success: function (data) {
                                $('#row-'+id).remove();
                                Swal.fire({
                                    title: "Başarılı",
                                    text: "Onaylanmıştır.",
                                    confirmButtonText: 'Tamam',
                                    icon: "success"
                                });

                            },
                            error: function (){
                                console.log("hata geldi");
                            }
                        })
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
            @else
            $('.btnChangeStatus').click(function () {
                let id = $(this).data('id');
                let self = $(this);
                Swal.fire({
                    title: 'Status değiştirmek istediğinize emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('articles.pending-approval.changeStatus') }}",
                            data: {
                                id : id
                            },
                            async:false,
                            success: function (data) {
                                if(data.comment_status)
                                {
                                    self.removeClass('btn-danger');
                                    self.addClass('btn-success');
                                    self.text('Aktif');
                                    Swal.fire({
                                        title: "Başarılı",
                                        text: "Yorumun durumu aktif olarak güncellendi",
                                        confirmButtonText: 'Tamam',
                                        icon: "success"
                                    });
                                }
                                else
                                {
                                    self.removeClass('btn-success');
                                    self.addClass('btn-danger');
                                    self.text('Pasif');
                                    Swal.fire({
                                        title: "Başarılı",
                                        text: "Yorumun durumu pasif olarak güncellendi",
                                        confirmButtonText: 'Tamam',
                                        icon: "success"
                                    });
                                }

                            },
                            error: function (){
                                console.log("hata geldi");
                            }
                        })
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
            @endif

            $('.btnDelete').click(function () {
                let id = $(this).data('id');
                let categoryName = $(this).data('name');

                Swal.fire({
                    title: categoryName + ' i Silmek istediğinize emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('articles.pending-approval.delete') }}",
                            data: {
                                "_method": "DELETE",
                                id : id
                            },
                            async:false,
                            success: function (data) {
                                let aElement = document.createElement('a');
                                aElement.className = 'btn btn-primary btn-sm btnRestore'
                                aElement.setAttribute('data-name', id);
                                aElement.setAttribute('data-id', id);
                                aElement.setAttribute('title', 'Geri Al');
                                aElement.href = 'javascript:void(0)';

                                let iElement = document.createElement('i');
                                iElement.className = 'material-icons ms-0';
                                iElement.innerHTML = 'undo';
                                aElement.append(iElement);

                                let actions = document.getElementsByClassName('actions-'+ id);
                                actions[0].appendChild(aElement);

                                Swal.fire({
                                    title: "Başarılı",
                                    text: "Yorum Silindi",
                                    confirmButtonText: 'Tamam',
                                    icon: "success"
                                });
                            },
                            error: function (){
                                console.log("hata geldi");
                            }
                        })
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

            $(document).on('click','body .btnRestore',function () {
                let id = $(this).data('id');

                let self = $(this);
                Swal.fire({
                    title: id + ' i Geri almak istediğinize emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('articles.comment.restore') }}",
                            data: {
                                id : id
                            },
                            async:false,
                            success: function (data) {

                                self.remove();
                                Swal.fire({
                                    title: "Başarılı",
                                    text: "Yorum Yayına Geri Alındı",
                                    confirmButtonText: 'Tamam',
                                    icon: "success"
                                });
                            },
                            error: function (){
                                console.log("hata geldi");
                            }
                        })

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

            $('#selectParentCategory').select2();

            $("#created_at").flatpickr({
                dateFormat: "Y-m-d",
            });

            $(".lookComment").click(function (){
                let comment = $(this).data("comment");
                $('#modalBody').text(comment);
            });
        });
    </script>
    <script>
        const popover = new bootstrap.Popover('.example-popover', {
            container: 'body'
        })
    </script>
@endsection

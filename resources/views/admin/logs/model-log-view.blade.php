<x-bootstrap.table
    :class="'table-striped table-hover table-responsive'"
    :is-responsive="1">

    <x-slot:rows>
        @if($logType == 'App\Models\User')
            <tr>
                <td>Image</td>
                <td>
                    @if(!empty($data->image))
                        <img src="{{ asset($data->image) }}" height="60" data-aos="flip-right" alt="Kullanıcı Resmi">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Name</td>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <td>Username</td>
                <td>{{ $data->username }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $data->email }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    @if($data->status)
                        <a href="javascript:void(0)" class="btn btn-success btn-sm">Aktif</a>
                    @else
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm">Pasif</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Is Admin</td>
                <td>
                    @if($data->is_admin)
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm">Admin</a>
                    @else
                        <a href="javascript:void(0)" class="btn btn-secondary btn-sm">User</a>
                    @endif
                </td>
            </tr>
        @elseif($logType == 'App\Models\Category')
            <tr>
                <td>Name</td>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <td>Slug</td>
                <td>{{ $data->slug }}</td>
            </tr>
            <tr>
                <td>Description</td>
                <td>{{ $data->description }}</td>
            </tr>
            <tr>
                <td>Order</td>
                <td>{{ $data->order }}</td>
            </tr>
            <tr>
                <td>Parent Category</td>
                <td>{{ $data->category?->name }}</td>
            </tr>
            <tr>
                <td>User</td>
                <td>{{ $data->user?->name }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    @if($data->status)
                        <a href="javascript:void(0)" class="btn btn-success btn-sm">Aktif</a>
                    @else
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm">Pasif</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Feature Status</td>
                <td>
                    @if($data->feature_status)
                        <a href="javascript:void(0)" class="btn btn-success btn-sm">Aktif</a>
                    @else
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm">Pasif</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Created Date</td>
                <td>{{ $data->created_at }}</td>
            </tr>
            <tr>
                <td>Updated Date</td>
                <td>{{ $data->updated_at }}</td>
            </tr>
        @elseif($logType == 'App\Models\Article')
            <tr>
                <td>Title</td>
                <td>{{ $data->title }}</td>
            </tr>
            <tr>
                <td>Slug</td>
                <td>{{ $data->slug }}</td>
            </tr>
            <tr>
                <td>Body</td>
                <td>{{ $data->body }}</td>
            </tr>
            <tr>
                <td>Tags</td>
                <td>{{ $data->tags }}</td>
            </tr>
            <tr>
                <td>Parent Category</td>
                <td>{{ $data->category?->name }}</td>
            </tr>
            <tr>
                <td>User</td>
                <td>{{ $data->user?->name }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    @if($data->status)
                        <a href="javascript:void(0)" class="btn btn-success btn-sm">Aktif</a>
                    @else
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm">Pasif</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>View Count</td>
                <td>{{ $data->view_count }}</td>
            </tr>
            <tr>
                <td>like Count</td>
                <td>{{ $data->like_count }}</td>
            </tr>
            <tr>
                <td>Read Time</td>
                <td>{{ $data->read_time }}</td>
            </tr>
            <tr>
                <td>Publish Date</td>
                <td>{{ $data->publish_date }}</td>
            </tr>
            <tr>
                <td>Created Date</td>
                <td>{{ $data->created_at }}</td>
            </tr>
            <tr>
                <td>Updated Date</td>
                <td>{{ $data->updated_at }}</td>
            </tr>
        @elseif($logType == 'App\Models\Settings')
            <tr>
                <td>Logo</td>
                <td>
                    @if(!empty($data->logo))
                        <img src="{{ asset($data->logo) }}" height="60" data-aos="flip-right" alt="Kullanıcı Resmi">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Category Default Image</td>
                <td>
                    @if(!empty($data->category_default_image))
                        <img src="{{ asset($data->category_default_image) }}" height="60" data-aos="flip-right" alt="Kullanıcı Resmi">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Article Default Image</td>
                <td>
                    @if(!empty($data->article_default_image))
                        <img src="{{ asset($data->article_default_image) }}" height="60" data-aos="flip-right" alt="Kullanıcı Resmi">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Default Comment Profile Image</td>
                <td>
                    @if(!empty($data->default_comment_profile_image))
                        <img src="{{ asset($data->default_comment_profile_image) }}" height="60" data-aos="flip-right" alt="Kullanıcı Resmi">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Reset Password Image</td>
                <td>
                    @if(!empty($data->reser_password_image))
                        <img src="{{ asset($data->reser_password_image) }}" height="60" data-aos="flip-right" alt="Kullanıcı Resmi">
                    @endif
                </td>
            </tr>
            <tr>
                <td>Telegram Link</td>
                <td>{{ $data->telegram_link }}</td>
            </tr>
            <tr>
                <td>Header Text</td>
                <td>{!! $data->header_text !!}</td>
            </tr>
        @elseif($logType == 'App\Models\ArticleComment')
            @if($data->user)
                <tr>
                    <td>Üye Adı</td>
                    <td>{{ $data->user?->name }}</td>
                </tr>
                <tr>
                    <td>Üye Email</td>
                    <td>{{ $data->user?->email }}</td>
                </tr>
            @else
                <tr>
                    <td>Ziyaretçi Adı</td>
                    <td>{{ $data->name }}</td>
                </tr>
                <tr>
                    <td>Ziyaretçi Email</td>
                    <td>{{ $data->email }}</td>
                </tr>
            @endif
            <tr>
                <td>Makale Başlık</td>
                <td><a target="_blank" href="{{ route('front.articleDetail', ['user' => $data->user->username, 'article' => $data->article->slug]) }}">
                        {{ $data->article->title }}
                    </a>
                </td>
            </tr>
            @if($data->parent)
                 <tr>
                     <td>Üst Yorum</td>
                     <td>{{ $data->parent->comment }}</td>
                 </tr>
            @endif
            <tr>
                <td>Yorum</td>
                <td>{{ $data->comment }}</td>
            </tr>
            <tr>
                <td>Ip Adresi</td>
                <td>{{ $data->ip }}</td>
            </tr>
            <tr>
                <td>Yorum Tarihi</td>
                <td>{{ $data->created_at }}</td>
            </tr>
        @endif
    </x-slot:rows>
</x-bootstrap.table>

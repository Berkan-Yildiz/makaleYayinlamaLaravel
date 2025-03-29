<div class="app-sidebar">
    <div class="logo">
        <a href="{{ route('admin.index') }}">
            <img src="{{ asset($settings->logo) }}" class="img-fluid">
        </a>
    </div>
    <div class="app-menu">
        <ul class="accordion-menu">
            <li class="sidebar-title">
                Berkan Yıldız
            </li>
            <li class="{{ Route::is("admin.index") ? "open" : "" }}">
                <a href="{{ route('admin.index') }}" class="{{ Route::is("admin.index") ? "active" : "" }}">
                    <i class="material-icons-two-tone">dashboard</i>
                    Dashboard
                </a>
            </li>
            <li class="{{ Route::is("articles.index") ||
                          Route::is("articles.create") ||
                          Route::is("articles.comment.list") ||
                          Route::is("articles.pending-approval") ? "open" : "" }}">
                <a href="{{ route('articles.index') }}" class="">
                    <i class="material-icons">tune</i>
                    Makale Yönetimi
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('articles.create') }}" class="{{ Route::is("articles.create") ? "active" : "" }}">Makale Oluştur</a>
                    </li>
                    <li>
                        <a href="{{ route('articles.index') }}" class="{{ Route::is("articles.index") ? "active" : "" }}">Makale Listesi</a>
                    </li>
                    <li>
                        <a href="{{ route('articles.comment.list') }}" class="{{ Route::is("articles.comment.list") ? "active" : "" }}">Yorum Listesi</a>
                    </li>
                    <li>
                        <a href="{{ route('articles.pending-approval') }}" class="{{ Route::is("articles.pending-approval") ? "active" : "" }}">Onay Bekleyen Yorumlar</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Route::is('categories.create') || Route::is("categories.index") ? "open" : "" }}">
                <a href="#" class="">
                    <i class="material-icons">tune</i>
                    Kategori Yönetimi
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('categories.create') }}" class="{{ Route::is("categories.create") ? "active" : "" }}">Kategori Oluştur</a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="{{ Route::is("categories.index") ? "active" : "" }}">Kategori Listesi</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Route::is("user.index") || Route::is("user.create") ? "open" : "" }}">
                <a href="#" class="">
                    <i class="material-icons">person</i>
                    Kullanıcı Yönetimi
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('user.create') }}" class="{{ Route::is("user.create") ? "active" : "" }}">Kullanıcı Oluştur</a>
                    </li>
                    <li>
                        <a href="{{ route('user.index') }}" class="{{ Route::is("user.index") ? "active" : "" }}">Kullanıcı Listesi</a>
                    </li>
                </ul>
            </li>
            <li class="{{ Route::is("admin.email-themes.create") ||
                          Route::is("admin.email-themes.index") ||
                          Route::is("admin.email-themes.edit") ||
                          Route::is("admin.email-themes.assign") ? "open" : "" }}">
                <a href="#" class="">
                    <i class="material-icons">tune</i>
                        Email Yönetimi
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('admin.email-themes.create') }}" class="{{ Route::is("admin.email-themes.create") ? "active" : "" }}">Yeni Tema Oluşturma</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.email-themes.index') }}" class="{{ Route::is("admin.email-themes.index") ? "active" : "" }}">Tema Listesi</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.email-themes.assign') }}" class="{{ Route::is("admin.email-themes.assign") ? "active" : "" }}">Tema Atama/Seçimi</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.email-themes.assign-list') }}" class="{{ Route::is("admin.email-themes.assign-list") ? "active" : "" }}">Tema Atama Listesi</a>
                    </li>
                </ul>
            </li>
            <li class="#">
                <a href="#" class="">
                    <i class="material-icons">tune</i>
                    Main Page
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('home') }}">Main Page</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Route::is('settings' ? 'open' : '') }}">
                <a href="{{ route('settings') }}" class="{{ Route::is("settings") ? "open" : "" }}">
                    <i class="material-icons-two-tone">settings</i>
                    Ayarlar
                </a>
            </li>

            <li class="{{ Route::is('dbLogs' ? 'open' : '') }}">
                <a href="{{ route('dbLogs') }}" class="{{ Route::is("dbLogs") ? "open" : "" }}">
                    <i class="material-icons-two-tone">settings</i>
                    Log Yönetimi
                </a>
            </li>
        </ul>
    </div>
</div>

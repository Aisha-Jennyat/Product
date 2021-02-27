<a class="navbar-brand" href="{{ url('/') }}">Almatin Company</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav font-weight-lighter">
        <li class="nav-item {{ $navHome??'' }}">
            <a class="nav-link" href="{{ route('index') }}">الصفحة الرئيسية <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item {{ $navProduction??'' }}">
            <a class="nav-link" href="{{ route('production.index') }}">إدخال الإنتاج</a>
        </li>
        @if(request()->user()['grants'] == 'admin')
        <li class="nav-item {{ $navManagement??'' }}">
            <a class="nav-link" href="{{ route('management') }}">إدارة الموظفين</a>
        </li>
        @endif
        <li class="nav-item {{ $navStatistics??'' }}">
            <a class="nav-link" href="{{ route('statistics') }}">إحصائيات</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">تسجيل الخروج</a>
        </li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </ul>
</div>
<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <li class="{{ Request::is('/') ? 'active' : '' }}">
            <a href="{{ route('dashboard.index') }}">
                <i class="fa fa-tachometer"></i> Overview
            </a>
        </li>
        <li class="{{ Request::is('parts/*') ? 'active' : '' }}">
            <a href="{{ route('parts.index') }}"><i class="fa fa-cubes"></i> Parts</a>
        </li>
    </ul>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-faded">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" data-toggle="collapse" class="navbar-toggle d-lg-none float-left">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span><span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="navbar-container">
      <div id="navbarSupportedContent" class="collapse navbar-collapse">
        <ul class="navbar-nav">
          @include('apex.include.notifications')
          <li class="dropdown nav-item">
            <a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle" aria-expanded="false">
              <i class="ft-user font-medium-3 blue-grey darken-4"></i>
              <p class="d-none">User Settings</p>
            </a>
            <div ngbdropdownmenu="" aria-labelledby="dropdownBasic3" class="dropdown-menu dropdown-menu-right">
              <a href="{!! url('admin/profile') !!}" class="dropdown-item py-1">
                <i class="ft-edit mr-2"></i>
                <span>@lang('user.label.profile') </span>
              </a>
              <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                <i class="ft-power mr-2"></i>
                <span>@lang('user.label.logout') </span>
              </a>
              <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
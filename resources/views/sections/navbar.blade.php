<nav class="navbar navbar-expand-md navbar-default main-navbar">
    <a class="navbar-brand"
       href="/"
       id="navbar-brand">
        {{ env('APP_NAME') }}
        <span class="dropdown-toggle hide-in-md hide-in-lg hide-in-xl"></span>
    </a>
    @auth
        <a class="navbar-user-avatar-sm hide-in-md hide-in-lg hide-in-xl"
           id="navbar-user-avatar-sm"
           data-toggle="dropdown"
           role="button"
           aria-haspopup="true"
           aria-expanded="false">
            <span class="dropdown-toggle hide-in-xs"></span>
            <span class="hide-in-xs">{{ $navbar['name'] }}</span>
            <img src="{{ $navbar['avatar'] }}"
                 class="img-fluid">
            <span data-to-farsi
                  @if(!$navbar['notifications_count'])
                  style="background: inherit"
                  @endif
                  class="notifications-badge-side-avatar-sm notifications-badge-counter">
                  @if($navbar['notifications_count'])
                    {{ $navbar['notifications_count'] }}
                @endif
            </span>
        </a>
        <div class="dropdown-menu navbar-user-dropdown-sm"
             id="navbar-user-dropdown-sm">
            <a class="dropdown-item my-page"
               href="/users">
                <i class="fas fa-home"></i>صفحه ی من
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item notifications"
               id="notifications-button-sm">
                <i class="far fa-bell"></i>اعلانات
                <span data-to-farsi
                      @if(!$navbar['notifications_count'])
                      style="background: inherit"
                      @endif
                      class="notifications-badge-inside-dropdown-sm notifications-badge-counter">
                    @if($navbar['notifications_count'])
                        {{ $navbar['notifications_count'] }}
                    @endif
                </span>
            </a>
            <div class="notifications-container"
                 id="notifications-container-sm">
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item logout"
               href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>خروج
            </a>
            <div class="dropdown-divider"></div>
        </div>
    @else
        <div class="hide-in-md hide-in-lg hide-in-xl login-register">
            <div class="nav-item">
                <a class="nav-link btn btn-light"
                   href="{{ route('login') }}">ورود</a>
            </div>
            <div class="nav-item">
                <a class="nav-link btn btn-light"
                   href="{{ route('register') }}">ثبت نام</a>
            </div>
        </div>
    @endauth
    <div class="collapse navbar-collapse" id="main-nav-items-wrapper">
        <ul class="col-12 col-md-7 col-lg-8 navbar-nav main-nav-items">
            <li class="nav-item hide-in-xl hide-in-lg hide-in-md">
                <a class="nav-link btn btn-light"
                   href="/">صفحه ی اصلی</a>
            </li>
            <li class="dropdown-divider hide-in-xl hide-in-lg hide-in-md"></li>
            <li class="nav-item">
                <a class="nav-link btn btn-light"
                   href="#"
                   data-toggle="tooltip"
                   data-placement="top"
                   title="به زودی">پیش بینی</a>
            </li>
            <li class="dropdown-divider hide-in-xl hide-in-lg hide-in-md"></li>
            <li class="nav-item">
                <a class="nav-link btn btn-light"
                   href="#"
                   data-toggle="tooltip"
                   data-placement="top"
                   title="به زودی">فوتبال فانتزی</a>
            </li>
            <li class="dropdown-divider hide-in-xl hide-in-lg hide-in-md"></li>
            <li class="nav-item">
                <a class="nav-link btn btn-light live-score"
                   href="#"
                   data-toggle="tooltip"
                   data-placement="top"
                   title="به زودی">نتایج زنده</a>
            </li>
            <li class="dropdown-divider hide-in-xl hide-in-lg hide-in-md"></li>
            <li class="nav-item hide-in-xs hide-in-sm">
                <a class="nav-link btn btn-light" id="navbar-btn-search">جست جو</a>
            </li>
            <li class="nav-item hide-in-xl hide-in-lg hide-in-md">
                <form class="form-inline main-nav-form-inline">
                    <input class="form-control navbar-input-form"
                           type="text"
                           placeholder="به دنبال چه هستید ؟"
                           aria-label="Search">
                    <button class="btn navbar-submit-form"
                            type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </li>
            <li class="dropdown-divider hide-in-xl hide-in-lg hide-in-md"></li>
        </ul>
        <div class="col-12 col-md-5 col-lg-4 hide-in-xs hide-in-sm">
            @auth
                <div class="nav-item dropdown">
                    <a data-toggle="dropdown"
                       class="navbar-user-avatar-lg"
                       id="navbar-user-avatar-lg"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <span class="dropdown-toggle">
                        </span>
                        {{ $navbar['name'] }}
                        <img src="{{ $navbar['avatar'] }}"
                             class="img-fluid">
                        <span data-to-farsi
                              @if(!$navbar['notifications_count'])
                              style="background: inherit"
                              @endif
                              class="notifications-badge-side-avatar-lg notifications-badge-counter">
                              @if($navbar['notifications_count'])
                                {{ $navbar['notifications_count'] }}
                            @endif
                        </span>
                    </a>
                    <div class="dropdown-menu navbar-user-dropdown-lg">
                        <a class="dropdown-item my-page"
                           href="{{ $navbar['url'] }}">
                            <i class="fas fa-home"></i>صفحه ی من
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item notifications"
                           id="notifications-button-lg">
                            <i class="far fa-bell"></i>اعلانات
                            <span @if(!$navbar['notifications_count'])
                                  style="background: inherit"
                                  @endif
                                  @if($navbar['notifications_count'])
                                  {{ $navbar['notifications_count'] }}
                                  @endif
                                  data-to-farsi
                                  class="notifications-badge-inside-dropdown-lg notifications-badge-counter">
                            </span>
                        </a>
                        <div class="notifications-container"
                             id="notifications-container-lg">
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item logout"
                           href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>خروج
                        </a>
                        <form id="logout-form"
                              action="{{ route('logout') }}"
                              method="POST"
                              hidden>
                            @csrf
                        </form>
                    </div>
                </div>
            @else
                <div class="login-register">
                    <div class="nav-item">
                        <a class="nav-link btn btn-light"
                           href="{{ route('login') }}">ورود</a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link btn btn-light"
                           href="{{ route('register') }}">ثبت نام</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
    <form class="form-inline main-nav-form-inline hide"
          method="post"
          action="">
        @csrf
        <button class="btn navbar-close-form"
                type="button"
                id="navbar-close-form">
            <i class="fas fa-times"></i>
        </button>
        <input class="form-control navbar-input-form"
               type="text"
               name="name"
               autofocus
               placeholder="به دنبال چه هستید ؟"
               aria-label="Search">
        <button class="btn navbar-submit-form"
                type="submit">
            <i class="fas fa-search"></i>
        </button>
    </form>
</nav>

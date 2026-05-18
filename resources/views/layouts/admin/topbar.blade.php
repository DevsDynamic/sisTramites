<header class="navbar navbar-expand-md d-print-none">

    <div class="container-xl">

        <div class="navbar-nav flex-row order-md-last">

            <div class="nav-item dropdown">

                <a href="#"
                   class="nav-link d-flex lh-1 text-reset p-0"
                   data-bs-toggle="dropdown">

                    <span class="avatar avatar-sm">

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </span>

                    <div class="d-none d-xl-block ps-2">

                        <div>

                            {{ auth()->user()->name }}

                        </div>

                        <div class="mt-1 small text-secondary">

                            Administrador

                        </div>

                    </div>

                </a>

                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit"
                                class="dropdown-item">

                            Cerrar sesión

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header>
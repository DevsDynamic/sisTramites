<aside class="navbar navbar-vertical navbar-expand-lg"
       data-bs-theme="dark">

    <div class="container-fluid">

        <h1 class="navbar-brand navbar-brand-autodark">

            SisTrámites

        </h1>

        <div class="collapse navbar-collapse show">

            <ul class="navbar-nav pt-lg-3">

                <li class="nav-item">

                    <a class="nav-link"
                       href="{{ route('admin.dashboard') }}">

                        <span class="nav-link-icon">

                            <i class="ti ti-dashboard"></i>

                        </span>

                        <span class="nav-link-title">

                            Dashboard

                        </span>

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link"
                       href="{{ route('plans.index') }}">

                        <span class="nav-link-icon">

                            <i class="ti ti-package"></i>

                        </span>

                        <span class="nav-link-title">

                            Planes

                        </span>

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link"
                       href="{{ route('tenants.index') }}">

                        <span class="nav-link-icon">

                            <i class="ti ti-users"></i>

                        </span>

                        <span class="nav-link-title">

                            Clientes

                        </span>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</aside>
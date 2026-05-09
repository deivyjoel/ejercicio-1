<?php if ($usu_rol === 1) { ?>
    <div class="vertical-menu">
        <div data-simplebar class="h-100">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Menu</li>
                    <li>
                        <a href="../../views/inicio/" class="waves-effect">
                            <div class="d-inline-block icons-sm me-1"><i class="mdi mdi-home"></i></div>
                            <span>Inicio</span>
                        </a>    
                    </li>
                    <li>
                        <a href="../../views/Turno/" class="waves-effect">
                            <div class="d-inline-block icons-sm me-1"><i class="mdi mdi-history"></i></div>
                            <span>Turnos</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../views/Servicio" class="wave-effect">
                            <div class="d-inline-block icons-sm me-1"><i class="mdi mdi-briefcase-outline"></i></div>
                            <span>Servicios</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

<?php } elseif ($usu_rol === 2) { ?>
    <div class="vertical-menu">
        <div data-simplebar class="h-100">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Menu</li>
                    <li>
                        <a href="../../views/Inicio/" class="waves-effect">
                            <div class="d-inline-block icons-sm me-1"><i class="mdi mdi-home"></i></div>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../views/Turno/" class="waves-effect">
                            <div class="d-inline-block icons-sm me-1"><i class="mdi mdi-history"></i></div>
                            <span>Historial de turnos</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

<?php } ?>
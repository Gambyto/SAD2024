  <nav class="sidebar">
    <div class="sidebar-top-wrapper">
      <div class="sidebar-top">
        <a href="#" class="logo__wrapper">
          <img src="../assets/img/Logo 2.3.png" alt="SAD Logo" class="logo-small">
          <span class="hide company-name">
            S.A.D
          </span>
        </a>
      </div>
      <button class="expand-btn" type="button">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6.00979 2.72L10.3565 7.06667C10.8698 7.58 10.8698 8.42 10.3565 8.93333L6.00979 13.28"
            stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>

<?php if ($_SESSION['type'] == 'Gerencia' || $_SESSION['type'] == 'Administrador') {
  //Muestra el menu completo?>
    <div class="sidebar-links-wrapper">
      <div class="sidebar-links">
        <ul>
          <li>
            <a href="Dashboard.php" title="Dashboard" >
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-layout-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 12a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2zm10 -4a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 -8a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2z" /></svg>
              <span class="link hide">Dashboard</span>
            </a>
          </li>

          <li>
            <a href="#Administracion" title="Administración" class="main-item">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2"> <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path> <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path> </svg> 
              <span class="link hide">Administración</span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="hide expand-icon"
                width="44"
                height="44"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 6l6 6l-6 6" />
              </svg>
            </a>
            <ul class="hide">
              <li>
                <a href="Empleados.php" title="Empleados">
                  Empleados
                </a>
              </li>
              <li>
                <a href="Usuarios.php" title="Usuarios">
                  Usuarios
                </a>
              </li>
            </ul>
          </li>

          <li>
            <a href="#bank-accounts" title="Nomina" class="main-item" >
              <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-building-bank"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg>
              <span class="link hide">Nomina</span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="hide expand-icon"
                width="44"
                height="44"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 6l6 6l-6 6" />
              </svg>
            </a>
            <ul class="hide">
              <li>
                <a href="Sueldos.php" title="Sueldos">
                  Sueldos
                </a>
              </li>
              <li>
                <a href="Vacaciones.php" title="Vacaciones">
                  Vacaciones
                </a>
              </li>
              <li>
                <a href="Fideicomiso.php" title="Fideicomisos">
                  Fideicomisos
                </a>
              </li>
              <li>
                <a href="#payouts" title="Liquidaciones">
                  Liquidaciones
                </a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#transfers" title="Transfers" class="main-item">
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-repeat"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 12v-3a3 3 0 0 1 3 -3h13m-3 -3l3 3l-3 3" /><path d="M20 12v3a3 3 0 0 1 -3 3h-13m3 3l-3 -3l3 -3" /></svg>
              <span class="link hide">Deducciones</span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="hide expand-icon"
                width="44"
                height="44"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 6l6 6l-6 6" />
              </svg>
            </a>
            <ul class="hide">
              <li>
                <a href="ISLR.php" title="Impuestos sobre la renta">
                  ISLR
                </a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#wallets" title="Prestamos" class="main-item">
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
              <span class="link hide">Prestamos</span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="hide expand-icon"
                width="44"
                height="44"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 6l6 6l-6 6" />
              </svg>
            </a>
            <ul class="hide">
              <li>
                <a href="PrestamoFinanza.php" title="Prestamos Financieros">
                  Financieros 
                </a>
              </li>
            </ul>
          </li>
          <li>
            <a href="Recibos.php" title="Recibos"  class="main-item">
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-analytics"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17l0 -5" /><path d="M12 17l0 -1" /><path d="M15 17l0 -3" /></svg>              
              <span class="link hide">Recibos</span>
            </a>
          </li>
<?php }elseif ($_SESSION['type'] == 'Trabajador') {?>
  <div class="sidebar-links-wrapper">
      <div class="sidebar-links">
        <ul>
          <li>
            <a href="Recibos.php" title="Recibos"  class="main-item">
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-analytics"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17l0 -5" /><path d="M12 17l0 -1" /><path d="M15 17l0 -3" /></svg>              
              <span class="link hide">Recibos</span>
            </a>
          </li>
        </ul>
      </div>
  </div>
<?php } ?>
        </ul>
      </div>
    </div>
    <div class="sidebar__profile">
      <div class="avatar__wrapper">
        <img class="avatar" src="../assets/img/user_image.png" alt="user Picture">
        <div class="online__status"></div>
      </div>
      <div class="avatar__name hide">
        <div class="user-name"><?php echo $_SESSION['nombre']; ?></div>
        <div class="email"><?php echo $_SESSION['type']; ?></div>
      </div>
      <a href="../PHP/logout.php" class="logout">
        <svg class="logout" xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-logout"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
      </a>
    </div>
  </nav>

  
  <script src="../JS/menu1.js"></script>

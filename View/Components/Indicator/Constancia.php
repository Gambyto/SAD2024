<div class="indicator__content" style="min-width: 19rem; min-height: 10px; margin-top: 1rem; cursor: pointer"
     onclick="window.open('../View/PlantillaPDF/Constancia-trabajo.php?cedula=<?php echo urlencode($_SESSION['id']); ?>', '_blank')">

    <div class="indicator__header">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="#000000"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M14 3v4a1 1 0 0 0 1 1h4" />
          <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
          <path d="M9 17h6" />
          <path d="M9 13h6" />
        </svg>

        <?php if (isset($_SESSION['nombre'])): ?>
            <span class="badge bg-secondary"><?php echo $_SESSION['nombre']; ?></span>
        <?php endif; ?>
    </div>

    <div class="indicator__body">
        <h5 class="text-body-primary">Generar Constancia de Trabajo</h5>
    </div>

</div>
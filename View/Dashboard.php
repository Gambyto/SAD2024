<?php

use PhpParser\Node\Expr\Include_;

 include_once 'Components/Header.php';?>

</header>

    <main>
        <div class="Dashboard">
            <div class="block Name">
                <h2>Dashboard</h2>
            </div>
            <div class="k1">
                <div class="kpi-s ke1">
                    <?php include_once '../View/Components/Indicator/Fechas_Ingreso.php';?>
                </div>
                <div class="kpi-l ke2">
                    <?php include_once '../View/Components/Indicator/Genero-dash.php';?>
                </div>
                <div class="kpi-s ke3">
                    <?php include_once '../View/Components/Indicator/solicitudes.php';?>
                </div>
                <div class="kpi-s ke4">
                    <?php include_once '../View/Components/Indicator/discapacidad.php';?>
                </div>
            </div>
            <div class="k2">
                <div class="kpi-l kf1 indicator_l">
                    <?php include_once '../View/Components/Indicator/MedidorPagos.php';?>
                </div>
                <div class="kpi-l kf2 indicator_l">
                    <?php include_once '../View/Components/Indicator/Vendedores.php';?>
                </div>
           <?php include_once '../View/Components/Indicator/Indicadores_Prestamos.php';?>
           <?php include_once '../View/Components/Modals/Modal_Prestamos.php';?>
            </div>
            <div class="k3">
                <div class="kpi-s kf7">
                    <?php include_once '../View/Components/Indicator/vacation_indicator.php';?>
                </div>
                <div class="kpi-s kf8">
                    <?php include_once '../View/Components/Indicator/ISLR_indicator.php';?>
                </div>
            </div>
            
        </div>
    </main>
<script src="../JS/Close_modal.js"></script>
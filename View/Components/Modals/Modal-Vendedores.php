
    <style>
        .modal-body-custom {
            padding: 0;
            overflow: hidden;
            height: 70vh;
            position: relative;
        }
        
        .slides-container {
            display: flex;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }
        
        .slide {
            min-width: 100%;
            height: 100%;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .graph-area {
            background: white;
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .node {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin: 20px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        
        .node:hover {
            transform: scale(1.1);
        }
        
        .form-area {
            background: white;
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            color: #333;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 100;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .nav-arrow:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }
        
        .nav-arrow.left {
            left: 20px;
        }
        
        .nav-arrow.right {
            right: 20px;
        }
        
        .nav-arrow.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .slide-indicator {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        
        .indicator-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .indicator-dot.active {
            background: white;
            width: 30px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="modal fade" id="vendedores" tabindex="-1" aria-labelledby="graphModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="graphModalLabel">
                        <i class="fas fa-chart-network me-2"></i>Reporte de vendedores
                    </h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body-custom">
                    <!-- Flechas de navegación -->
                    <button class="nav-arrow left bg-dark" id="prevSlide">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <button class="nav-arrow right bg-dark" id="nextSlide">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    
                    <!-- Contenedor de slides -->
                    <div class="slides-container" id="slidesContainer">
                        <!-- Slide 1: Grafo -->
                        <div class="slide slide-graph">
                            <div class="graph-area">
                                <h3 class="text-center mb-4">Grafo de Nodos</h3>
                                <div class="text-center">
                                    <div class="node">Nodo 1</div>
                                    <i class="fas fa-arrow-right mx-3"></i>
                                    <div class="node">Nodo 2</div>
                                    <i class="fas fa-arrow-right mx-3"></i>
                                    <div class="node">Nodo 3</div>
                                </div>
                                <div class="text-center mt-4">
                                    <div class="node">Nodo 4</div>
                                    <i class="fas fa-arrow-right mx-3"></i>
                                    <div class="node">Nodo 5</div>
                                    <i class="fas fa-arrow-right mx-3"></i>
                                    <div class="node">Nodo 6</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Slide 2: Formulario -->
                        <div class="slide slide-form">
                            <div class="form-area">
                                <h3 class="text-center mb-4">Configuración de Nodo</h3>
                                <form id="nodeForm">
                                    <div class="mb-3">
                                        <label for="nodeName" class="form-label fw-bold">Nombre del Nodo</label>
                                        <input type="text" class="form-control form-control-lg" id="nodeName" placeholder="Ingrese el nombre">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nodeType" class="form-label fw-bold">Tipo</label>
                                        <select class="form-select form-select-lg" id="nodeType">
                                            <option selected>Seleccione un tipo</option>
                                            <option value="1">Proceso</option>
                                            <option value="2">Decisión</option>
                                            <option value="3">Inicio/Fin</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nodeDescription" class="form-label fw-bold">Descripción</label>
                                        <textarea class="form-control" id="nodeDescription" rows="4" placeholder="Descripción del nodo"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nodeColor" class="form-label fw-bold">Color</label>
                                        <input type="color" class="form-control form-control-color w-100" id="nodeColor" value="#667eea">
                                    </div>
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="nodeActive" checked>
                                        <label class="form-check-label fw-bold" for="nodeActive">
                                            Nodo Activo
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-save me-2"></i>Guardar Cambios
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Indicadores de slide -->
                    <div class="slide-indicator">
                        <div class="indicator-dot active" data-slide="0"></div>
                        <div class="indicator-dot" data-slide="1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentSlide = 0;
        const totalSlides = 2;
        const container = document.getElementById('slidesContainer');
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');
        const indicators = document.querySelectorAll('.indicator-dot');
        const modalBody = document.querySelector('.modal-body-custom');
        
        function updateSlide(index) {
            currentSlide = index;
            container.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Actualizar indicadores
            indicators.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
            
            // Mostrar/ocultar flechas
            prevBtn.classList.toggle('hidden', currentSlide === 0);
            nextBtn.classList.toggle('hidden', currentSlide === totalSlides - 1);
        }
        
        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                updateSlide(currentSlide + 1);
            }
        }
        
        function prevSlide() {
            if (currentSlide > 0) {
                updateSlide(currentSlide - 1);
            }
        }
        
        // Event listeners para las flechas
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);
        
        // Event listeners para los indicadores
        indicators.forEach((dot, index) => {
            dot.addEventListener('click', () => updateSlide(index));
        });
        
        // Soporte para teclas de flecha
        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('graphModal');
            if (modal.classList.contains('show')) {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            }
        });
        
        // Soporte para scroll/wheel
        let scrollTimeout;
        modalBody.addEventListener('wheel', (e) => {
            e.preventDefault();
            
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                if (e.deltaY > 0) {
                    nextSlide();
                } else if (e.deltaY < 0) {
                    prevSlide();
                }
            }, 50);
        }, { passive: false });
        
        // Soporte para touch/swipe en móviles
        let touchStartX = 0;
        let touchEndX = 0;
        
        modalBody.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        modalBody.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                nextSlide();
            }
            if (touchEndX > touchStartX + 50) {
                prevSlide();
            }
        }
        
        // Manejo del formulario
        document.getElementById('nodeForm').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('¡Datos guardados exitosamente!');
        });
        
        // Inicializar
        updateSlide(0);
    </script>
</body>
</html>
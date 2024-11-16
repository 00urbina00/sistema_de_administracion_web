<!-- menu.php -->
<div class="logo">LOGO</div>
<nav>
    <a href="index.php">Home</a>
    <a href="productos.php">Productos</a>
    <a href="contacto.php">Contacto</a>

    <?php if (isset($_SESSION['id_cliente'])): ?>
        <?php
            if (!isset($_SESSION['productCount'])) {
                $_SESSION['productCount'] = 0;
            }
        ?>
        <a href="carrito_1.php">Carrito(<span id="productCount"><?php echo $_SESSION['productCount']; ?></span>)</a>
        <a href="#"><?php echo htmlspecialchars($_SESSION['nombre']); ?></a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    <?php else: ?>
        <a href="iniciar_sesion.php">Iniciar sesión</a>
    <?php endif; ?>
</nav>
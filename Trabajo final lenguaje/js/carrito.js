document.addEventListener('DOMContentLoaded', () => {
    const carritoItemsDiv = document.getElementById('carrito-items');
    const carritoCantidadSpan = document.getElementById('carrito-cantidad');
    const carritoSubtotalSpan = document.getElementById('carrito-subtotal');
    const carritoTotalSpan = document.getElementById('carrito-total');
    const carritoVacioMensaje = document.getElementById('carrito-vacio');

    let carrito = cargarCarrito();
    actualizarContadorCarrito(); // Actualizar al cargar la página
    renderizarCarrito();
    actualizarTotalCarrito();

    function cargarCarrito() {
        const carritoGuardado = localStorage.getItem('carrito');
        return carritoGuardado ? JSON.parse(carritoGuardado) : [];
    }

    function guardarCarrito() {
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }

    function renderizarCarrito() {
        if (!carritoItemsDiv) return; // Asegurarse de que el elemento existe

        carritoItemsDiv.innerHTML = '';
        if (carrito.length === 0) {
            if (carritoVacioMensaje) carritoVacioMensaje.style.display = 'block';
        } else {
            if (carritoVacioMensaje) carritoVacioMensaje.style.display = 'none';
            carrito.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('carrito-item');
                itemDiv.dataset.id = item.id;
                itemDiv.innerHTML = `
                    <div class="item-imagen">
                        <img src="${item.imagen}" alt="${item.nombre}">
                    </div>
                    <div class="item-detalles">
                        <h3>${item.nombre}</h3>
                        <p>${item.descripcion ? item.descripcion : ''}</p>
                        <p>Precio unitario: <span class="precio-unitario">${item.precio.toFixed(2)}</span> €</p>
                        <div class="item-cantidad">
                            <label for="cantidad-${item.id}">Cantidad:</label>
                            <input type="number" id="cantidad-${item.id}" name="cantidad-${item.id}" value="${item.cantidad}" min="1">
                        </div>
                    </div>
                    <div class="item-acciones">
                        <button class="boton-eliminar" data-id="${item.id}">Eliminar</button>
                    </div>
                    <div class="item-subtotal">
                        Subtotal: <span class="precio-subtotal">${(item.precio * item.cantidad).toFixed(2)}</span> €
                    </div>
                `;
                carritoItemsDiv.appendChild(itemDiv);
            });
        }
    }

    function actualizarContadorCarrito() {
        if (carritoCantidadSpan) {
            const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
            carritoCantidadSpan.textContent = totalItems;
        }
    }

    function actualizarTotalCarrito() {
        if (carritoSubtotalSpan && carritoTotalSpan) {
            const subtotalPrecio = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            carritoSubtotalSpan.textContent = subtotalPrecio.toFixed(2);
            carritoTotalSpan.textContent = subtotalPrecio.toFixed(2);
        }
    }

    // Event listener para los botones "Añadir al Carrito" en la página de camisetas
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('boton-añadir-carrito')) {
            const id = event.target.dataset.id;
            const nombre = event.target.dataset.nombre;
            const precio = parseFloat(event.target.dataset.precio);
            const imagen = event.target.dataset.imagen;
            const descripcion = ''; // Puedes añadir la descripción si la tienes en el HTML

            agregarAlCarrito(id, nombre, precio, imagen, descripcion);
        }
    });

    function agregarAlCarrito(id, nombre, precio, imagen, descripcion) {
        const itemExistente = carrito.find(item => item.id === id);

        if (itemExistente) {
            carrito = carrito.map(item =>
                item.id === id ? { ...item, cantidad: item.cantidad + 1 } : item
            );
        } else {
            carrito.push({ id, nombre, precio, imagen, cantidad: 1, descripcion: descripcion });
        }

        guardarCarrito();
        actualizarContadorCarrito();
        console.log(`Item con ID ${id} añadido al carrito.`);
        // No renderizar el carrito aquí, se hace en la página del carrito
    }

    // Event listener para eliminar items del carrito (en la página del carrito)
    if (carritoItemsDiv) {
        carritoItemsDiv.addEventListener('click', (event) => {
            if (event.target.classList.contains('boton-eliminar')) {
                const itemId = parseInt(event.target.dataset.id);
                carrito = carrito.filter(item => item.id !== itemId);
                guardarCarrito();
                renderizarCarrito();
                actualizarContadorCarrito();
                actualizarTotalCarrito();
                console.log(`Item con ID ${itemId} eliminado.`);
            }
        });

        carritoItemsDiv.addEventListener('change', (event) => {
            if (event.target.tagName === 'INPUT' && event.target.type === 'number') {
                const itemId = parseInt(event.target.id.split('-')[1]);
                const nuevaCantidad = parseInt(event.target.value);
                carrito = carrito.map(item =>
                    item.id === itemId ? { ...item, cantidad: nuevaCantidad } : item
                );
                guardarCarrito();
                renderizarCarrito();
                actualizarTotalCarrito();
            }
        });
    }
});
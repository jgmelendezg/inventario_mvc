// mensajes.js

function mostrarMensaje(tipo = "success", texto = "") {
    const contenedor = document.getElementById("mensajes");
    if (!contenedor) return;

    const clase = tipo === "success" ? "alert-success"
                : tipo === "error"   ? "alert-danger"
                : tipo === "warning" ? "alert-warning"
                : "alert-info";

    contenedor.innerHTML = `
        <div class="alert ${clase} alert-dismissible fade show" role="alert">
            ${texto}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    `;
}


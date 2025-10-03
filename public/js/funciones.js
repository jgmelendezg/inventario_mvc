// funciones.js

document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.querySelector("form[data-form]");
    if (!formulario) return;

    const tipo = formulario.getAttribute("data-form");

    const validaciones = {
        equipos: {
            nombre_pc: validarNombre,
            ip: validarIP,
            mac: validarMAC,
            service_tag: validarServiceTag,
            sistema_operativo: validarSistemaOperativo
        },
        usuarios: {
            nombre: validarNombreUsuario,
            cargo: validarCargo,
            usuario_sistema: validarUsuarioSistema,
            contrasena: validarContrasena,
            correo: validarCorreo,
            area: validarArea
        }
    };

    const campos = validaciones[tipo];
    if (!campos) return;

    Object.keys(campos).forEach(nombreCampo => {
        const input = document.querySelector(`[name="${nombreCampo}"]`);
        if (input) {
            input.addEventListener("input", () => {
                const valor = input.value;
                const esValido = campos[nombreCampo](valor);
                input.style.borderColor = esValido ? "green" : "red";
            });
        }
    });
});

// Validaciones para equipos
function validarIP(ip) {
    const regex = /^\d{1,3}(\.\d{1,3}){3}$/;
    return regex.test(ip);
}

function validarMAC(mac) {
    const regex = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/;
    return regex.test(mac);
}

function validarNombre(nombre) {
    return nombre.trim().length >= 3;
}

function validarServiceTag(tag) {
    return tag.trim().length >= 5;
}

function validarSistemaOperativo(so) {
    return so.trim().length >= 3;
}

// Validaciones para usuarios
function validarNombreUsuario(nombre) {
    return nombre.trim().length >= 3;
}

function validarCargo(cargo) {
    return cargo.trim().length >= 3;
}

function validarUsuarioSistema(usuario) {
    return usuario.trim().length >= 3;
}

function validarContrasena(pass) {
    return pass.trim().length >= 6;
}

function validarCorreo(correo) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(correo);
}

function validarArea(area) {
    return area.trim().length >= 3;
}

// Mostrar errores (si lo usas en validación final)
function mostrarErrores(errores) {
    const contenedor = document.getElementById("errores");
    if (!contenedor) return;
    contenedor.innerHTML = "";
    errores.forEach(error => {
        const p = document.createElement("p");
        p.textContent = error;
        p.style.color = "red";
        contenedor.appendChild(p);
    });
}
cargarTurnoActivo();
cargarHistorial();

async function cargarTurnoActivo() {
    try {
        // Se hace un fetch get al backend
        const response = await fetch("/banco_sistema_atc/turnos/activo", {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        // Se verifica el tipo de respuesta
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error("Respuesta inesperada:", text);
            return;
        }

        const data = await response.json();

        const cardTurno   = document.getElementById("turno-activo");
        const btnReservar = document.getElementById("btn-reservar-turno");

        const t = data.status === "success" ? data.data : null;
        console.log(t)

        // Se construye la card turno si un turno activo
        if (t) {
            document.getElementById("turno-numero").textContent = `${t.tur_pre}${String(t.tur_n_tur).padStart(3, '0')}`;
            document.getElementById("turno-servicio").textContent = t.ser_nom;
            document.getElementById("turno-estado").textContent   = formatearEstado(t.tur_est);

            const btnCancelar = document.getElementById("btn-cancelar-turno");
            if (t.tur_est !== "pendiente") {
                // Si el usuario ya está siendo atendido no puede cancelar
                btnCancelar.style.display = "none";
            } else {
                btnCancelar.addEventListener("click", () => cancelarTurno(t.tur_id));
            }

            cardTurno.style.display   = "flex";
            btnReservar.style.display = "none";

        } else {
            // Si no hay un turno activo entonces ocultamos la card Turno y mostramos el boton reservar
            cardTurno.style.display   = "none";
            btnReservar.style.display = "block";

            btnReservar.addEventListener("click", () => {
                CargarVista("/banco_sistema_atc/views/usuario/reservar_turno.html", "/banco_sistema_atc/views/usuario/reservar_turno.js");
            });
        }

    } catch (error) {
        console.error("Error en fetch:", error);
    }
}

async function cancelarTurno(tur_id) {
    const confirmar = confirm("¿Seguro que querés cancelar tu turno?");
    if (!confirmar) return;

    try {
        // Se hace un fetch de tipo post al backend
        const response  = await fetch("/banco_sistema_atc/turnos/cancelar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ tur_id })
        });

        // Se verifica el tipo de respuesta
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error("Respuesta inesperada:", text);
            return;
        }

        const data = await response.json();

        if (data.status === "success") {
            cargarTurnoActivo();
            cargarHistorial();
        } else {
            console.error("Error en respuesta:", data);
        }
    } catch (error) {
        console.error("Error en fetch:", error);
    }
}

async function cargarHistorial() {
    try {
        // Se hace un fetch de tipo get al backend
        const response = await fetch("/banco_sistema_atc/turnos/historial", {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        // Se verifica el tipo de respuesta
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error("Respuesta inesperada:", text);
            return;
        }

        const data = await response.json();

        const lista = document.getElementById("historial-lista");

        if (data.status === "success" && data.data && data.data.length > 0) {
            lista.innerHTML = data.data.map(t => `
                <div class="historial-item">
                    <span class="historial-numero">${t.tur_pre}${String(t.tur_n_tur).padStart(3, '0')}</span>
                    <span class="historial-servicio">${t.ser_nom}</span>
                    <span class="historial-estado historial-estado--${t.tur_est}">${formatearEstado(t.tur_est)}</span>
                    <span class="historial-fecha">${formatearFecha(t.tur_fec_hor)}</span>
                </div>
            `).join("");
        } else {
            lista.innerHTML = `<p class="historial-vacio">No hay turnos anteriores.</p>`;
        }

    } catch (error) {
        console.error("Error en fetch:", error);
    }
}


function formatearEstado(estado) {
    const map = {
        pendiente:   "Pendiente",
        en_atencion: "En atención",
        atendido:    "Atendido",
        cancelado:   "Cancelado"
    };
    return map[estado] ?? estado;
}

function formatearFecha(fecha) {
    if (!fecha) return "";
    return new Date(fecha).toLocaleDateString("es-AR", {
        day: "2-digit", month: "2-digit", year: "numeric"
    });
}
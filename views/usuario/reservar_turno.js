
cargarServicios();

async function cargarServicios() {
    try {
        // Se hace un fetch get al backend
        const response = await fetch("/banco_sistema_atc/servicios", {
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

        // Se construye la estructura la lista de sercicios dinámicmente
        if (data.status === "success") {
            const contenedor = document.getElementById("servicios");

            contenedor.innerHTML = "";

            data.data.forEach(servicio => {
                const card = document.createElement("div");
                card.classList.add("card-servicio");
                card.innerHTML = `
                    <h4>${servicio.ser_nom}</h4>
                    <p>${servicio.ser_dur_prom} minutos</p>
                `;

                card.addEventListener("click", () => {
                    seleccionarServicio(servicio.ser_id, servicio.ser_nom, card);
                });

                contenedor.appendChild(card);
            });

        } else {
            console.error("Error en respuesta:", data);
        }

    } catch (error) {
        console.error("Error en fetch:", error);
    }
}

async function seleccionarServicio(ser_id, ser_nom, card) {
    const confirmar = confirm(`¿Confirmas el turno para "${ser_nom}"?`);
    if (!confirmar) return;

    card.style.pointerEvents = "none";
    card.style.opacity = "0.6";

    try {
        // Se hace un fetch get al backend
        const response = await fetch("/banco_sistema_atc/turnos/reservar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ ser_id: ser_id })
        });

        // Se verifica el tipo de respuesta
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error("Respuesta inesperada:", text);
            card.style.pointerEvents = "";
            card.style.opacity = "";
            return;
        }

        const data = await response.json();

        // Si se reservo correctamente se envia a inicio
        if (data.status === "success") {
            CargarVista("/banco_sistema_atc/views/usuario/inicio.html", "/banco_sistema_atc/views/usuario/inicio.js");
        } else {
            console.error("Error en respuesta:", data);
            card.style.pointerEvents = "";
            card.style.opacity = "";
        }

    } catch (error) {
        console.error("Error en fetch:", error);
        card.style.pointerEvents = "";
        card.style.opacity = "";
    }
}
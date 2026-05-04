// SCRIPT GLOBAL queda enganchada siempre al objeto window. (Los demás scripts podrán usar la función cargarVista)
function CargarVista(ruta, script = null){
    fetch(ruta)
    .then(res => res.text())
    .then(html =>{
        const cont = document.querySelector(".content");
        cont.innerHTML = html;


        if (script){
            // Elimina el script de vista anterior antes de cargar el nuevo
            const anterior = document.getElementById("vista-script");
            if (anterior) anterior.remove();

            const s = document.createElement("script");
            s.src = script;
            s.defer = true;
            document.body.appendChild(s);
        }
    })
}

// Que lo primero que se recargue sea inicio
document.addEventListener("DOMContentLoaded", () =>{
    CargarVista("/banco_sistema_atc/views/usuario/inicio.html", "/banco_sistema_atc/views/usuario/inicio.js")
})


// Boton de inicio
document.getElementById("btn-inicio").addEventListener("click", function(e){
    e.preventDefault();
    CargarVista("/banco_sistema_atc/views/usuario/inicio.html", "/banco_sistema_atc/views/usuario/inicio.js")
})


// Boton de desloguearse
document.getElementById("btn-logout").addEventListener("click", async function(e){
    e.preventDefault();
    const response = await fetch("/banco_sistema_atc/auth/logout", {
        method: "POST"
    });

    const data = await response.json();

    if (data.status === "success"){
        window.location.href = "/banco_sistema_atc/views/login/inicio_sesion.html";
    }
});

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

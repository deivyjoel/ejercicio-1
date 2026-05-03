function mostrar(cual) {
    document.getElementById('card-login').classList.add('oculto');
    document.getElementById('card-registro').classList.add('oculto');
    document.getElementById('card-' + cual).classList.remove('oculto');
}

// ======================
// LOGIN
// ======================
document.getElementById("form-login").addEventListener("submit", async function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!email || !password) {
        alert("Completa todos los campos");
        return;
    }

    try {
        // Se hace un fetch al backend
        const response = await fetch("/banco_sistema_atc/auth/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        });

        // Se verifica el tipo de respuesta
        const ContentType = response.headers.get("content-type");

        if(!ContentType || !ContentType.includes("application/json")){
            const text = await response.text();
            console.error("Respuesta inesperada", text)
            alert("El servidor respondió de forma inesperada")
            return;
        }

        const data = await response.json();

        if (!response.ok){
            alert(data.message || "Error en el servidor");
            return; 
        }

        if (data.status === "success") {
            alert("Bienvenido");
            window.location.href = "/banco_sistema_atc/views/index.php";
        } else {
            alert(data.message);
        }

    } catch (error) {
        alert("Error del servidor");
        console.log(error.message)
    }
});


// ======================
// REGISTRO
// ======================
document.getElementById("form-registro").addEventListener("submit", async function (e) {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const email = document.getElementById("email-reg").value.trim();
    const password = document.getElementById("password-reg").value.trim();

    if (!nombre || !email || !password) {
        alert("Completa todos los campos");
        return;
    }

    try {
        // Se hace un fetch al backend
        const response = await fetch("/banco_sistema_atc/auth/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                nombre: nombre,
                email: email,
                password: password
            })
        });
         
        // Se verifica el tipo de respuesta
        const ContentType = response.headers.get("content-type");

        if(!ContentType || !ContentType.includes("application/json")){
            const text = await response.text();
            console.error("Respuesta inesperada", text)
            alert("El servidor respondió de forma inesperada")
            return;
        }

        const data = await response.json();

        if (!response.ok){
            alert(data.message || "Error en el servidor");
            return; 
        }

        if (data.status === "success") {
            alert("Cuenta creada correctamente");
            mostrar("login");
        } else {
            alert(data.message || "Error desconocido");
        }

    } catch (error) {
        alert("Error de conexión o del servidor");
    }
});
document.addEventListener("DOMContentLoaded", () => {
    const items = document.querySelectorAll(".plan-item");
    const areaTitulo = document.getElementById("titulo-plan");
    const areaTexto = document.getElementById("texto-plan");
    const areaLista = document.getElementById("lista-caracteristicas");
    const btnDetalles = document.getElementById("btn-detalles");
    const box = document.getElementById("caracteristicas-box");


    if (items.length > 0) {
        mostrarPlan(items[0]); // primer plan visible
    }

    items.forEach(item => {
        item.addEventListener("click", () => {
            mostrarPlan(item);
        });
    });

    function mostrarPlan(item) {
        const html = JSON.parse(item.dataset.descripcion);
        const temp = document.createElement("div");
        temp.innerHTML = html;

        const titulo = item.querySelector("h2").innerText;

        const ul = temp.querySelector("ul");
        let listaHTML = "";

        if (ul) {
            listaHTML = ul.outerHTML;
            ul.remove();
        }

        areaTitulo.textContent = titulo;
        areaTexto.innerHTML = temp.innerHTML;
        areaLista.innerHTML = listaHTML;
        btnDetalles.href = "/usuario/register/" + item.dataset.idEncrypted;
        // ✅ Color reactivo para borde, botón y lista
        box.style.border = "3px solid " + item.dataset.marco;
        box.style.setProperty("--color-marco", item.dataset.marco);
    }
    
});

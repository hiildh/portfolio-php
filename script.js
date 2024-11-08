// script.js
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM fully loaded and parsed");
    const menuToggle = document.querySelector(".menu-toggle");
    const menuLateral = document.querySelector(".menu-lateral");

    menuToggle.addEventListener("click", () => {
        if (menuLateral.style.display === "block") {
            menuLateral.style.display = "none";
            return;
        } else {
            menuLateral.style.display = "block";
        }
    });

    document.addEventListener("click", (event) => {
        if (!menuLateral.contains(event.target) && !menuToggle.contains(event.target) && window.innerWidth <= 768) {
            menuLateral.style.display = "none";
        }
    });

    // Form validation
    const form = document.querySelector("#cadastro form");
    const dataNascimentoInput = document.querySelector("#data_nascimento");
    const telefoneInput = document.querySelector("#telefone");

    // Formatação do Telefone enquanto o usuário digita
    telefoneInput.addEventListener("input", (event) => {
        console.log(event.target.value);
        let input = event.target.value.replace(/\D/g, "");
        if (input.length > 11) {
        input = input.substring(0, 11);
        }
        const formattedPhone = input.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
        event.target.value = formattedPhone;
    });

    form.addEventListener("submit", (event) => {
        // Validação da Data de Nascimento
        const dataNascimento = new Date(dataNascimentoInput.value);
        const hoje = new Date();
        const idade = hoje.getFullYear() - dataNascimento.getFullYear();
        const mes = hoje.getMonth() - dataNascimento.getMonth();

        if (idade < 18 || (idade === 18 && mes < 0) || (idade === 18 && mes === 0 && hoje.getDate() < dataNascimento.getDate())) {
            alert("Você precisa ter pelo menos 18 anos para se cadastrar.");
            event.preventDefault(); // Impede o envio do formulário
            return;
        }

        // Validação do Telefone
        const telefonePattern = /^\(\d{2}\) \d{5}-\d{4}$/;
        if (!telefonePattern.test(telefoneInput.value)) {
            alert("Por favor, insira um número de telefone válido no formato (xx) xxxxx-xxxx.");
            event.preventDefault();
            return;
        }
    });
});

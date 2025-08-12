document.addEventListener("DOMContentLoaded", function () {
  console.log("Quiz Game carregado!");

  // Animação leve no alerta penalidade
  const penalties = document.querySelectorAll(".penalty-alert");
  penalties.forEach((el) => {
    // Define a transição
    el.style.transition = "transform 0.3s ease";

    // Aplica o efeito de "zoom" sutil
    el.style.transform = "scale(1.05)";

    // Volta ao tamanho normal após 300ms
    setTimeout(() => {
      el.style.transform = "scale(1)";
    }, 300);
  });
});

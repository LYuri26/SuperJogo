document.addEventListener("DOMContentLoaded", () => {
  const rows = document.querySelectorAll("table tbody tr");
  const rankingTable = document.getElementById("rankingTable");
  const btnTopo = document.getElementById("btnTopo");

  // Fade-in suave da tabela inteira
  if (rankingTable) {
    rankingTable.style.opacity = 0;
    rankingTable.style.transition = "opacity 1s ease";
    setTimeout(() => {
      rankingTable.style.opacity = 1;
    }, 100);
  }

  // Destacar linha clicada (toggle) com remoção das outras
  rows.forEach((row) => {
    row.style.transition = "background-color 0.4s ease, transform 0.3s ease";
    row.addEventListener("click", () => {
      rows.forEach((r) => r.classList.remove("table-active"));
      row.classList.add("table-active");
    });

    // Zoom suave ao passar o mouse
    row.addEventListener("mouseenter", () => {
      row.style.transform = "scale(1.04)";
      row.style.boxShadow = "0 6px 25px rgba(37, 117, 252, 0.4)";
      showTooltip(row);
    });
    row.addEventListener("mouseleave", () => {
      row.style.transform = "scale(1)";
      row.style.boxShadow = "none";
      hideTooltip();
    });
  });

  // Tooltip simples para mostrar pontos da equipe ao passar o mouse
  const tooltip = document.createElement("div");
  tooltip.style.position = "fixed";
  tooltip.style.padding = "6px 12px";
  tooltip.style.background = "rgba(37, 117, 252, 0.85)";
  tooltip.style.color = "#fff";
  tooltip.style.borderRadius = "6px";
  tooltip.style.fontSize = "0.9rem";
  tooltip.style.pointerEvents = "none";
  tooltip.style.transition = "opacity 0.3s ease";
  tooltip.style.opacity = 0;
  tooltip.style.zIndex = 9999;
  document.body.appendChild(tooltip);

  function showTooltip(row) {
    const pontos = row.querySelector("td.points")?.textContent || "";
    if (!pontos) return;
    tooltip.textContent = `Pontos: ${pontos.trim()}`;
    tooltip.style.opacity = 1;
    document.addEventListener("mousemove", moveTooltip);
  }

  function moveTooltip(e) {
    tooltip.style.top = e.clientY + 20 + "px";
    tooltip.style.left = e.clientX + 20 + "px";
  }

  function hideTooltip() {
    tooltip.style.opacity = 0;
    document.removeEventListener("mousemove", moveTooltip);
  }

  // Animação pulsante contínua na linha campeã
  const firstRow = document.querySelector(
    "#rankingTable tbody tr.table-success"
  );
  if (firstRow) {
    firstRow.animate(
      [
        { boxShadow: "0 0 15px #2ecc71" },
        { boxShadow: "0 0 30px #27ae60" },
        { boxShadow: "0 0 15px #2ecc71" },
      ],
      {
        duration: 2000,
        iterations: Infinity,
        easing: "ease-in-out",
      }
    );
  }

  // Scroll suave para o topo ao clicar no título
  const titulo = document.querySelector("h1");
  if (titulo) {
    titulo.style.cursor = "pointer";
    titulo.title = "Clique para voltar ao topo";
    titulo.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // Botão "Voltar ao topo"
  if (btnTopo) {
    btnTopo.style.display = "none";
    btnTopo.style.position = "fixed";
    btnTopo.style.bottom = "30px";
    btnTopo.style.right = "30px";
    btnTopo.style.zIndex = 1000;
    btnTopo.style.borderRadius = "50%";
    btnTopo.style.width = "50px";
    btnTopo.style.height = "50px";
    btnTopo.style.fontSize = "1.8rem";
    btnTopo.style.backgroundColor = "#2575fc";
    btnTopo.style.color = "#fff";
    btnTopo.style.border = "none";
    btnTopo.style.boxShadow = "0 4px 12px rgba(37, 117, 252, 0.7)";
    btnTopo.style.transition = "opacity 0.4s ease";

    window.addEventListener("scroll", () => {
      if (window.scrollY > 200) {
        btnTopo.style.display = "block";
        btnTopo.style.opacity = 1;
      } else {
        btnTopo.style.opacity = 0;
        setTimeout(() => {
          btnTopo.style.display = "none";
        }, 400);
      }
    });

    btnTopo.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }
});

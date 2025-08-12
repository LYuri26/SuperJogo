document.addEventListener("DOMContentLoaded", function () {
  const teamNameInput = document.getElementById("teamName");
  const membersContainer = document.getElementById("membersContainer");
  const addMemberBtn = document.getElementById("addMemberBtn");
  const addTeamBtn = document.getElementById("addTeamBtn");
  const teamsList = document.getElementById("teamsList");
  const gameSection = document.getElementById("gameSection");
  const startGameBtn = document.getElementById("startGameBtn");
  const startGameForm = document.getElementById("startGameForm");

  let teams = [];

  function showAlert(message, type) {
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alertDiv.setAttribute("role", "alert");
    alertDiv.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    // Inserir logo abaixo do formulário de equipe, se existir; caso contrário no body
    const refNode = addTeamBtn ? addTeamBtn.parentNode : document.body;
    refNode.insertBefore(alertDiv, refNode.nextSibling);
    setTimeout(() => alertDiv.remove(), 3000);
  }

  function removeMember(button) {
    const allMembers = membersContainer.querySelectorAll(".input-group");
    if (allMembers.length > 1) {
      button.closest(".input-group").remove();
    } else {
      showAlert("Cada equipe deve ter pelo menos um membro!", "warning");
    }
  }

  if (addMemberBtn && membersContainer) {
    addMemberBtn.addEventListener("click", () => {
      const div = document.createElement("div");
      div.className = "input-group mb-2";
      div.innerHTML = `
        <input type="text" name="members[]" class="form-control member-input" placeholder="Nome do membro" required />
        <button class="btn btn-outline-danger remove-member" type="button"><i class="fas fa-times"></i></button>
      `;
      membersContainer.appendChild(div);
      div.querySelector(".remove-member").addEventListener("click", (e) => {
        removeMember(e.target.closest(".input-group"));
      });
    });
  }

  function updateTeamsList() {
    if (!teamsList) return;
    teamsList.innerHTML = "";
    teams.forEach((team) => {
      const card = document.createElement("div");
      card.className = "team-card border rounded p-3 mb-3";
      card.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="mb-0">${team.name}</h5>
          <button class="btn btn-sm btn-outline-danger" type="button" onclick="removeTeam(${team.id})">
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
        <p class="text-muted small mb-2">${team.members.length} membro(s)</p>
        <ul class="list-group list-group-flush">
          ${team.members.map((m) => `<li class="list-group-item p-1">${m}</li>`).join("")}
        </ul>
      `;
      teamsList.appendChild(card);
    });

    if (gameSection) {
      if (teams.length >= 2) {
        gameSection.classList.remove("d-none");
      } else {
        gameSection.classList.add("d-none");
      }
    }
  }

  window.removeTeam = function (teamId) {
    if (!confirm("Tem certeza que deseja remover esta equipe?")) return;
    fetch("process_team.php", {
      method: "POST",
      body: new URLSearchParams({ removeTeamId: teamId })
    })
      .then((res) => {
        if (!res.ok) throw new Error("Erro");
        return res.text();
      })
      .then(() => loadTeams())
      .catch(() => showAlert("Erro ao remover equipe!", "danger"));
  };

  if (addTeamBtn) {
    addTeamBtn.addEventListener("click", (e) => {
      // Let the form submit normally; but prevent if validation fails
      const teamName = teamNameInput.value.trim();
      const memberInputs = membersContainer.querySelectorAll(".member-input");
      const members = [];
      memberInputs.forEach((input) => {
        const val = input.value.trim();
        if (val) members.push(val);
      });

      if (!teamName) {
        e.preventDefault();
        showAlert("Digite um nome para a equipe!", "danger");
        teamNameInput.focus();
        return;
      }
      if (members.length === 0) {
        e.preventDefault();
        showAlert("Adicione pelo menos um membro!", "danger");
        return;
      }

      // If validation passes, do nothing and let the form submit to process_team.php
    });
  }

  function loadTeams() {
    fetch("get_teams.php")
      .then((res) => res.json())
      .then((data) => {
        teams = data;
        updateTeamsList();
      })
      .catch(() => {
        // fallback: do nothing
      });
  }

  if (startGameBtn) {
    startGameBtn.addEventListener("click", (e) => {
      // Validate totalQuestions but allow the form to submit to start_game.php
      const totalQuestionsInput = document.getElementById("totalQuestions");
      const totalQuestions = totalQuestionsInput ? parseInt(totalQuestionsInput.value) : NaN;
      if (isNaN(totalQuestions) || totalQuestions < 1 || totalQuestions > 100) {
        e.preventDefault();
        showAlert("O número de perguntas deve ser entre 1 e 100!", "danger");
        if (totalQuestionsInput) totalQuestionsInput.focus();
        return;
      }
      // let the form submit naturally (POST to start_game.php)
    });
  }

  loadTeams();
});

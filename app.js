document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".notice").forEach((notice) => {
    const closeBtn = document.createElement("button");
    closeBtn.textContent = "×";
    closeBtn.className = "notice-close";
    closeBtn.type = "button";

    closeBtn.addEventListener("click", () => notice.remove());
    notice.appendChild(closeBtn);

    setTimeout(() => notice.remove(), 4500);
  });

  const modal = document.getElementById("deleteModal");
  const confirmBtn = document.getElementById("modalConfirm");
  const cancelBtn = document.getElementById("modalCancel");

  if (!modal || !confirmBtn || !cancelBtn) return;

  let pendingForm = null;

  function openModal() {
    modal.classList.remove("hidden");
    modal.setAttribute("aria-hidden", "false");
  }

  function closeModal() {
    modal.classList.add("hidden");
    modal.setAttribute("aria-hidden", "true");
    pendingForm = null;
  }

  document.querySelectorAll(".js-delete").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      pendingForm = btn.closest("form");
      openModal();
    });
  });

  confirmBtn.addEventListener("click", () => {
    if (pendingForm) pendingForm.submit();
  });

  cancelBtn.addEventListener("click", closeModal);

  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !modal.classList.contains("hidden")) closeModal();
  });
});

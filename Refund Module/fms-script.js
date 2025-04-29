document.addEventListener("DOMContentLoaded", () => {
  // ------------------------------
  // Sidebar Toggle Script
  // ------------------------------
  const sidebar = document.querySelector(".sidebar");
  const toggleBtn = document.querySelector(".js-sidenav-toggle");

  if (sidebar && toggleBtn) {
    const icon = toggleBtn.querySelector("span");

    toggleBtn.addEventListener("click", () => {
      const isOpen = sidebar.classList.toggle("open");
      icon.classList.replace(isOpen ? "mdi-menu" : "mdi-close", isOpen ? "mdi-close" : "mdi-menu");
    });
  }

  // ------------------------------
  // Idle Timeout Script (3 min)
  // ------------------------------
  const IDLE_TIMEOUT = 3 * 60 * 1000;
  let idleTimer;

  const resetIdleTimer = () => {
    clearTimeout(idleTimer);
    idleTimer = setTimeout(() => {
      console.log("Redirecting due to inactivity...");
      window.location.href = "php/logout.php?timeout=1";
    }, IDLE_TIMEOUT);
  };

  ["mousemove", "mousedown", "keydown", "touchstart", "scroll"].forEach((evt) => {
    document.addEventListener(evt, resetIdleTimer, true);
  });

  resetIdleTimer();

  // ------------------------------
  // Refund Modal Script
  // ------------------------------
  const openBtn = document.getElementById("openRefundModal");
  const refundModal = document.getElementById("refundModal");
  const closeBtn = document.getElementById("closeRefundModal");

  if (openBtn && refundModal && closeBtn) {
    openBtn.addEventListener("click", () => {
      refundModal.style.display = "flex";
    });

    closeBtn.addEventListener("click", () => {
      refundModal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
      if (e.target === refundModal) {
        refundModal.style.display = "none";
      }
    });
  }

  // ------------------------------
  // Refund Request Form Submission
  // ------------------------------
  const refundForm = document.getElementById("refundRequestForm");
  const confirmationMsg = document.getElementById("confirmationMessage");

  if (refundForm) {
    refundForm.addEventListener("submit", (e) => {
      e.preventDefault();

      const orderNumber = refundForm.orderNumber?.value.trim();
      const reason = refundForm.reasonForRefund?.value.trim();
      const amount = parseFloat(refundForm.refundAmount?.value);

      if (!orderNumber || !reason || isNaN(amount) || amount <= 0) {
        alert("Please complete all fields with valid data.");
        return;
      }

      // Log for debugging – replace with real AJAX in production
      console.log("Refund Request Submitted:", {
        requestId: refundForm.requestId?.value,
        dateRequested: refundForm.dateRequested?.value,
        orderNumber,
        reason,
        amount,
      });

      // Reset & show confirmation
      refundForm.reset();
      if (confirmationMsg) {
        confirmationMsg.style.display = "block";
        setTimeout(() => {
          confirmationMsg.style.display = "none";
        }, 5000);
      }
    });
  }
});

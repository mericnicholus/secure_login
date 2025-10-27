document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("registerForm");
  const loginForm = document.getElementById("loginForm");

  // ---------------------------
  // Registration
  // ---------------------------
  if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirmPassword").value;
      const msg = document.getElementById("registerMessage");

      msg.textContent = "Processing...";
      msg.style.color = "blue";

      try {
        const res = await fetch("register.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ username, password, confirmPassword }),
        });
        const data = await res.json();

        msg.style.color = data.status === "success" ? "green" : "red";
        msg.textContent = data.message;

        if (data.status === "success") {
          setTimeout(() => (window.location.href = "index.html"), 1500);
        }
      } catch (err) {
        msg.style.color = "red";
        msg.textContent = "Server error: " + err.message;
      }
    });
  }

  // ---------------------------
  // Login
  // ---------------------------
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value;
      const msg = document.getElementById("loginMessage");

      msg.textContent = "Checking credentials...";
      msg.style.color = "blue";

      try {
        const res = await fetch("login.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ username, password }),
        });
        const data = await res.json();

        msg.style.color = data.status === "success" ? "green" : "red";
        msg.textContent = data.message;

        if (data.status === "success") {
          setTimeout(() => (window.location.href = "home.php"), 1500);
        }
      } catch (err) {
        msg.style.color = "red";
        msg.textContent = "Server error: " + err.message;
      }
    });
  }
});

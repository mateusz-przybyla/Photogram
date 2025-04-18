document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("themeToggleBtn");
  const html = document.documentElement;

  // Przy ładowaniu — sprawdź localStorage
  if (localStorage.getItem("theme") === "dark") {
    html.classList.add("dark");
  } else if (localStorage.getItem("theme") === "light") {
    html.classList.remove("dark");
  }

  toggleBtn.addEventListener("click", () => {
    html.classList.toggle("dark");
    const isDark = html.classList.contains("dark");
    localStorage.setItem("theme", isDark ? "dark" : "light");
  });
});

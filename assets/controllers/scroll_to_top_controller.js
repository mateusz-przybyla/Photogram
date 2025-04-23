import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["button"];

  connect() {
    window.addEventListener("scroll", this.toggleVisibility);
  }

  disconnect() {
    window.removeEventListener("scroll", this.toggleVisibility);
  }

  toggleVisibility = () => {
    if (window.pageYOffset > 300) {
      this.buttonTarget.classList.remove("hidden");
    } else {
      this.buttonTarget.classList.add("hidden");
    }
  };

  scrollToTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
}

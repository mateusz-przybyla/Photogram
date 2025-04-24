import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["input", "fileName", "error"];

  validateFile() {
    const input = this.inputTarget;
    const file = input.files[0];

    if (!file) {
      this.reset();
      return;
    }

    if (!["image/jpeg", "image/png"].includes(file.type)) {
      this.showError("Only JPG/PNG images are allowed.");
      this.reset();
      return;
    }

    if (file.size > 1024 * 1024) {
      this.showError("File is too large. Max 1MB allowed.");
      this.reset();
      return;
    }

    this.clearError();
    this.fileNameTarget.textContent = file.name;
  }

  showError(message) {
    this.errorTarget.textContent = message;
  }

  clearError() {
    this.errorTarget.textContent = "";
  }

  reset() {
    this.inputTarget.value = "";
    this.fileNameTarget.textContent = "";
  }
}

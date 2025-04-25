import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["input", "fileName", "error", "preview"];

  checkFile() {
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
    this.showPreview(file);
  }

  showPreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      this.previewTarget.src = e.target.result;
      this.previewTarget.classList.remove("hidden");
    };
    reader.readAsDataURL(file);

    const placeholder = this.element.querySelector(
      "[data-upload-image-target='placeholder']"
    );
    if (placeholder) {
      placeholder.classList.add("hidden");
    }
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
    this.previewTarget.src = "";
  }
}

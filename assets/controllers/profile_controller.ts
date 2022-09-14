import { Controller } from "@hotwired/stimulus";
import { Spinner } from "spin.js";

export default class extends Controller {
  static targets = ["upload", "form", "imageWrapper", "imageUploadButton", "imageDeleteButton", "fileInput"];

  private uploadTarget: HTMLDivElement;
  private formTarget: HTMLFormElement;
  private imageWrapperTarget: HTMLDivElement;
  private imageUploadButtonTarget: HTMLButtonElement;
  private imageDeleteButtonTarget: HTMLButtonElement;
  private fileInputTarget: HTMLInputElement;
  private spinner: Spinner;

  connect() {
    this.initSpinner();
    this.initEventListeners();
  }

  async submit(e) {
    e.preventDefault();

    // Check if file input is empty
    if (this.fileInputTarget.files.length === 0) {
      console.log("No file selected");
      return;
    }

    this.spinner.spin(this.imageWrapperTarget);

    const formData = new FormData(this.formTarget);

    // Use X-Requested-With: XMLHttpRequest to prevent CSRF
    const response = await fetch("/profile/upload", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    const data = await response.json();

    this.spinner.stop();

    if (!data.success) {
      return;
    }

    if (data.success) {
      let img: HTMLImageElement = this.imageWrapperTarget.querySelector("img");

      if (img === null) {
        this.imageWrapperTarget.innerHTML = "";
        img = document.createElement("img");
        this.imageWrapperTarget.appendChild(img);
      }

      img.src = data["image_url"];
    }
  }

  private initEventListeners() {
    this.imageUploadButtonTarget.addEventListener("click", () => {
      this.showUploadForm();
    });

    this.imageDeleteButtonTarget.addEventListener("click", () => {
      // TODO: Implement image deletion
    });
  }

  private initSpinner() {
    const spinnerSettings = {
      lines: 13, // The number of lines to draw
      length: 38, // The length of each line
      width: 17, // The line thickness
      radius: 45, // The radius of the inner circle
      scale: 0.3, // Scales overall size of the spinner
      corners: 1, // Corner roundness (0..1)
      speed: 1, // Rounds per second
      rotate: 0, // The rotation offset
      animation: "spinner-line-fade-quick", // The CSS animation name for the lines
      direction: 1, // 1: clockwise, -1: counterclockwise
      color: "#ffffff", // CSS color or array of colors
      fadeColor: "transparent", // CSS color or array of colors
      top: "50%", // Top position relative to parent
      left: "50%", // Left position relative to parent
      shadow: "0 0 1px transparent", // Box-shadow for the lines
      zIndex: 2000000000, // The z-index (defaults to 2e9)
      className: "profile__spinner", // The CSS class to assign to the spinner
      position: "absolute", // Element positioning
    };

    this.spinner = new Spinner(spinnerSettings);
  }

  /**
   * Show the upload form (or hide if already visible).
   *
   * @private
   */
  private showUploadForm() {
    if (this.uploadTarget.style.display === "none" || this.uploadTarget.style.display === "") {
      this.uploadTarget.style.display = "flex";
    } else {
      this.close();
    }
  }

  private close() {
    this.uploadTarget.style.display = "none";
  }
}

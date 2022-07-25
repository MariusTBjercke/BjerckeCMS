import { DOMReady } from "@assets/js/shared/domready";

class Alert {
  private readonly alertEl: HTMLElement;

  constructor() {
    this.alertEl = document.querySelector(".alert");

    if (this.alertEl !== null) {
      this.autoHide();
    }
  }

  /**
   * Automatically hide the alert after a certain amount of time.
   * Remember to change the delay value in the style as well.
   *
   * @private
   */
  private autoHide() {
    setTimeout(() => {
      this.alertEl.style.display = "none";
    }, 5000);
  }
}

DOMReady(() => {
  new Alert();
});

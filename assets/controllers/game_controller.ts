import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["newMap"];

  private newMapTarget: HTMLElement;
  private isMapFormVisible: boolean = false;

  connect() {
    this.addEventListeners();
  }

  private newMap() {
    this.isMapFormVisible = !this.isMapFormVisible;

    if (this.isMapFormVisible) {
      this.newMapTarget.classList.add("maps__form_show");
    } else {
      this.newMapTarget.classList.remove("maps__form_show");
    }
  }

  private addEventListeners() {

  }
}

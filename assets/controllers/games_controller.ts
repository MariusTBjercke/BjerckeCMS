import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["newMap"];

  private newMapTarget: HTMLElement;
  private isMapFormVisible: boolean = false;

  connect() {
    this.addEventListeners();
  }

  private createMap() {
    this.isMapFormVisible = !this.isMapFormVisible;
    const displayClass = "games__new-map_show";

    if (this.isMapFormVisible) {
      this.newMapTarget.classList.add(displayClass);
    } else {
      this.newMapTarget.classList.remove(displayClass);
    }
  }

  private addEventListeners() {

  }
}

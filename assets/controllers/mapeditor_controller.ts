import { Controller } from "@hotwired/stimulus";
import MapEditor from "@assets/js/components/MapEditor";

export default class extends Controller {
  static targets = ["editor"];

  private editorTarget: HTMLDivElement;

  connect() {
    this.init();
    this.addEventListeners();
  }

  private init() {
    new MapEditor(this.editorTarget);
  }

  private addEventListeners() {}
}

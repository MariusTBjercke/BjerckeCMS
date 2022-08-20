import { Controller } from "@hotwired/stimulus";
import Terminal from "@assets/js/components/Terminal";

export default class extends Controller<HTMLDivElement> {
  static targets = [];

  connect() {
    const terminal = new Terminal(this.element);
    terminal.run();
  }
}

import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["newMap", "mapEditor"];

  private newMapTarget: HTMLElement;
  private formTarget: HTMLFormElement;
  private mapEditorTarget: HTMLDivElement;

  connect() {
    this.formTarget = this.element.querySelector("form");
    this.addEventListeners();
  }

  private show() {
    if (this.newMapTarget.classList.contains("maps__new_show")) {
      this.newMapTarget.classList.remove("maps__new_show");
      return;
    }

    this.newMapTarget.classList.add("maps__new_show");
  }

  private hide() {
    this.newMapTarget.classList.remove("maps__new_show");
  }

  async submit(e) {
    e.preventDefault();

    const formData = new FormData(this.formTarget);

    // Use X-Requested-With: XMLHttpRequest to prevent CSRF
    const response = await fetch("/games/maps/new", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    const data = await response.json();

    if (data.serverError) {
      throw new Error(data.serverError);
    }

    if (data.success) {
      window.location.href = data.redirect;
    } else {
      console.log("Something went wrong, please try again.");
    }
  }

  private addEventListeners() {}
}

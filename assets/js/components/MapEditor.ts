export default class MapEditor {
  private editor: HTMLDivElement;
  private readonly imageSrc: string;
  private imageContainer: HTMLDivElement;
  private interactiveCanvas: HTMLDivElement;

  constructor(editor: HTMLDivElement) {
    this.editor = editor;
    this.imageSrc = editor.dataset.imageSrc;
    this.imageContainer = editor.querySelector(".mapeditor__background-container");
    this.interactiveCanvas = editor.querySelector(".mapeditor__interactive-canvas");

    this.init();
  }

  private init() {
    this.loadImage();
    this.addEventListeners();
  }

  private loadImage() {
    const image = new Image();
    image.src = this.imageSrc;
    image.onload = () => {
      this.imageContainer.appendChild(image);

      if (image.width > image.height) {
        if (image.width > this.imageContainer.offsetWidth) {
          image.style.width = this.imageContainer.offsetWidth + "px";
          this.imageContainer.style.height = image.offsetHeight + "px";
        }
      } else {
        if (image.height > this.imageContainer.offsetHeight) {
          image.style.height = this.imageContainer.offsetHeight + "px";
          this.imageContainer.style.width = image.offsetWidth + "px";
        }
      }
      this.editor.classList.remove("mapeditor__preload");
    };
  }

  private async interact(e: MouseEvent) {
    await this.addMarker(e);
  }

  private async addMarker(e: MouseEvent) {
    const mapId = this.editor.dataset.mapId;
    const x = e.offsetX;
    const y = e.offsetY;

    const formData = new FormData();
    formData.append("name", "test");
    formData.append("description", "test");
    formData.append("x", x.toString());
    formData.append("y", y.toString());
    formData.append("map_id", mapId);

    const response = await fetch("", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      console.log("Success!");
    }

    const marker = document.createElement("div");
    marker.classList.add("mapeditor__marker");
    marker.style.top = e.offsetY + -5 + "px";
    marker.style.left = e.offsetX + -5 + "px";
    this.imageContainer.appendChild(marker);
  }

  private addEventListeners() {
    this.interactiveCanvas.addEventListener("click", this.interact.bind(this));
  }
}

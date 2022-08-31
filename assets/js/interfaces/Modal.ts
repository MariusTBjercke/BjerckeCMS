export default interface Modal {
  element: HTMLElement;
  content: {
    element: HTMLElement;
    header: HTMLElement;
    body: HTMLElement;
    footer: HTMLElement;
  };
}

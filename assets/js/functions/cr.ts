/**
 * Create a custom HTML element.
 *
 * @param type Ex: "div", "span", "button", etc.
 * @param attributes Ex: { class: "terminal__text_cursor" }
 */
export default function cr(type: string, attributes: any): HTMLElement {
  const element = document.createElement(type);
  for (const key in attributes) {
    element.setAttribute(key, attributes[key]);
  }

  return element;
}

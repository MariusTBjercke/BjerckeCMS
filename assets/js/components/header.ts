import { DOMReady } from "@assets/js/shared/domready";

DOMReady(() => {
  const element = document.querySelector("nav");

  if (element) {
    const topLogo = element.querySelector(".top-logo");

    // Go to home page on logo click.
    topLogo.addEventListener("click", () => {
      const href = window.location.href;
      // window.location.href = href.substring(0, href.lastIndexOf('/'));
      window.location.href = "/";
    });

    // Mobile navigation dropdown
    const burgerBtn = element.querySelector(".navigation-wrapper__bars");
    const closeBtn = element.querySelector(".collapsed-navigation__close");
    const navigation = element.querySelector(".collapsed-navigation");
    const buttons = [burgerBtn, closeBtn];

    // Get session storage value and toggle if true
    const navSessionStorage = sessionStorage.getItem("navigation");
    if (navSessionStorage === "true") {
      navigation.classList.toggle("collapsed-navigation_open");
    }

    /**
     * Open/close navigation menu.
     */
    function toggleNavigation() {
      navigation.classList.toggle("collapsed-navigation_open");
      sessionStorage.setItem("navigation", navigation.classList.contains("collapsed-navigation_open").toString());
    }

    buttons.forEach((btn: HTMLElement) => btn.addEventListener("click", toggleNavigation));
  }
});

import { DOMReady } from "@assets/js/shared/domready";

DOMReady(() => {
  const element = document.querySelector("nav");

  if (element) {
    const logo: HTMLDivElement = element.querySelector(".top-logo");
    const wrapper: HTMLDivElement = element.querySelector(".navigation-wrapper");
    const burgerBtn: HTMLDivElement = wrapper.querySelector(".navigation-wrapper__bars");
    const navigation: HTMLDivElement = element.querySelector(".collapsed-navigation");
    const buttons = [burgerBtn];

    // Adjust the navigation position on page load
    navigation.style.top = `${wrapper.clientHeight}px`;

    // Go to home page on logo click.
    logo.addEventListener("click", () => {
      const href = window.location.href;
      // window.location.href = href.substring(0, href.lastIndexOf('/'));
      window.location.href = "/";
    });

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

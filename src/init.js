
// init.js: Prevent FOUC and register branded spinner
document.documentElement.setAttribute("data-theme", "light");

window.addEventListener("DOMContentLoaded", () => {
  document.documentElement.removeAttribute("data-cloak");
});

import { setBasePath } from '@shoelace-style/shoelace/dist/utilities/base-path.js';
import SlSpinner from '@shoelace-style/shoelace/dist/components/spinner/spinner.component.js';

// Set base path for Shoelace assets
setBasePath('/dist/shoelace');

// Optional: Customize branded spinner
SlSpinner.styles = [
  ...SlSpinner.styles,
  /* Add custom CSS overrides here */
];
customElements.define('sl-spinner', SlSpinner);

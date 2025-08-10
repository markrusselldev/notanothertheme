// init.js: Prevent FOUC and handle Shoelace setup
document.documentElement.setAttribute("data-theme", "light");

window.addEventListener("DOMContentLoaded", () => {
  document.documentElement.removeAttribute("data-cloak");
});

import { setBasePath } from '@shoelace-style/shoelace/dist/utilities/base-path.js';
import '@shoelace-style/shoelace/dist/components/spinner/spinner.js';
// DO NOT import header.js — it no longer exists in current Shoelace

setBasePath('/dist/vendor/shoelace');

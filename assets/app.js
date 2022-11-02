import "./scss/style.scss";

// Require all files from the paths below, do not put any unused files there!
requireAll(require.context("./scss/pages/", true, /\.scss$/));
requireAll(require.context("./scss/components/", true, /\.scss$/));
requireAll(require.context("./js/pages/", true, /\.js|ts$/));
requireAll(require.context("./js/components/", true, /\.js|ts$/));

// Start the Stimulus application
import "./bootstrap";

// Node modules
import "bootstrap";

// Shared
import "./js/shared/preload";

function requireAll(r) {
    r.keys().forEach(r);
}

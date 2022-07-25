/**
 * Custom AJAX form function.
 *
 * @param formData Data to be sent to the server
 * @param inputArr Array of input elements
 * @param callback Callback function
 */
function ajaxPostJson(formData, inputArr, callback) {
    fetch('/xmlhttprequest.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        response.json().then(data => {
            callback(data);

            // Clear the form if input array is not null
            if (inputArr !== null) {
                inputArr.forEach(input => {
                    input.value = '';
                });
            }
        });
    });
}

function ajaxPostHtml(formData, inputArr, callback) {
    fetch('/xmlhttprequest.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        response.text().then(data => {
            callback(data);

            // Clear the form if input array is not null
            if (inputArr !== null) {
                inputArr.forEach(input => {
                    input.value = '';
                });
            }
        });
    });
}

function renderTile(tileID, parent, script, options) {
    let formData = new FormData();
    formData.append('action', 'render_tile');
    formData.append('tile_id', tileID);

    if (options) {
        formData.append('options', JSON.stringify(options));
    }

    // Show a spinning icon while the tile is loading
    const loader = document.createElement('div');
    loader.classList.add('loader__spinner');
    parent.appendChild(loader);

    fetch('/xmlhttprequest.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        response.text().then(data => {
            loader.remove();

            parent.innerHTML = data;
            if (script) {
                script();
            }
        });
    });
}

export {ajaxPostJson, ajaxPostHtml, renderTile};
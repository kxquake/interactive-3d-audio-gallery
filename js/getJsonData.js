let productData = null;

$(document).ready(function() {
    $.getJSON('index.php?action=apiGetProducts')
        .done(function(data) {
            if (Array.isArray(data) && data.length > 0) {
                applyProductData(data);
            } else {
                fallbackToStaticJson();
            }
        })
        .fail(function() {
            console.log('API unavailable, using static JSON');
            fallbackToStaticJson();
        });
});

function fallbackToStaticJson() {
    $.getJSON('application/model/data.json')
        .done(function(data) {
            if (Array.isArray(data.products) && data.products.length) {
                applyProductData(data.products);
            } else {
                $('#product-description').text('Could not load product data.');
            }
        })
        .fail(function() {
            console.error('Both API and static JSON failed to load');
            $('#product-description').text('Could not load product data.');
        });
}

// SQLite returns snake_case columns; the JSON file uses camelCase. flatten to camelCase here
// so the rest of the code doesn't have to care which source the data came from.
function normaliseKeys(arr) {
    return arr.map(p => ({
        id: parseInt(p.id),
        title: p.title,
        description: p.description,
        modelFile: p.model_file || p.modelFile,
        thumbnail: p.thumbnail,
        hasAnimation: p.has_animation == 1 || p.hasAnimation === true,
        animationLabel: p.animation_label || p.animationLabel || ''
    }));
}

function applyProductData(data) {
    productData = normaliseKeys(data);
    if (window.currentProductId) window.populateProductInfo(window.currentProductId);
}

window.populateProductInfo = function(id) {
    window.currentProductId = id;
    if (!productData) return;
    const p = productData.find(x => x.id === id);
    if (!p) return;
    $('#product-title').text(p.title);
    $('#product-description').text(p.description);
};

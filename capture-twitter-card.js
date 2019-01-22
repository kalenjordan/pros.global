var page = require('webpage').create();
//viewportSize being the actual size of the headless browser
page.viewportSize = { width: 600, height: 337 };
//the clipRect is the portion of the page you are taking a screenshot of
page.clipRect = { top: 0, left: 0, width: 600, height: 337 };

var args = require('system').args;
var url = args[1];
var fileName = args[2];

console.log("Capturing: " + url);
var fileNamePath = 'public/img/generated/' + fileName;

// Open website
page.open(url, function(status) {
    // Show some message in the console
    console.log("Status:  " + status);
    console.log("Loaded:  " + page.url);

    page.render(fileNamePath);
    phantom.exit();
});
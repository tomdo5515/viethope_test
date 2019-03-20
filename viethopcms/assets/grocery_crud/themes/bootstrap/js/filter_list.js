var delayTimer;
function doSearch(text) {
    clearTimeout(delayTimer);
    delayTimer = setTimeout(function() {
        // Do the ajax stuff
    }, 2000); // Will do the ajax stuff after 2000 ms, or 2 s
}
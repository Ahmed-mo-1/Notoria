window.addEventListener('scroll', function () {
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    var scrollPercent = (scrollTop / scrollHeight) * 100;
    var progressBar = document.getElementById('page-progress-bar');
    if (progressBar) {
        progressBar.style.width = "".concat(scrollPercent, "%");
    }
});

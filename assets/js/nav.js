function togglePost(button){
	let currentArticle = button.closest('article');
	let articles = document.querySelectorAll("article");
	let filter = document.getElementById('category-filters');
	let backButton = document.getElementById('backButton');

	if(currentArticle){
	const content = currentArticle.querySelector('.post-content');
		articles.forEach(article => article.classList.toggle('hide'));
		filter.classList.toggle('hide');
		backButton.classList.toggle('hide');
		if(content){
			content.classList.toggle('show');
			currentArticle.classList.remove('hide');
		}
		document.documentElement.scrollTop = 0;

	}
}

function backToAll(){

	let articles = document.querySelectorAll("article");
	let filter = document.getElementById('category-filters');
	let backButton = document.getElementById('backButton');
	let postContents = document.querySelectorAll('.post-content');

	articles.forEach(article => article.classList.remove('hide'));
	postContents.forEach(postContent => postContent.classList.remove('show'));
	filter.classList.remove('hide');
	backButton.classList.add('hide');
	document.documentElement.scrollTop = 0;

}





/* copy-btn */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.copy-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const code = button.nextElementSibling.querySelector('code');
            if (code) {
                navigator.clipboard.writeText(code.innerText).then(() => {
                    button.textContent = 'Copied!';
                    setTimeout(() => button.textContent = 'Copy', 1500);
                });
            }
        });
    });
});

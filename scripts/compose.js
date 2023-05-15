let urlParams = new URLSearchParams(window.location.search);
var anime = urlParams.get('anime');// retrieves the anime id

document.querySelectorAll('input[type="radio"]')[0].click();
// selects the first tag so it's review by default

document.querySelector('div.postForm').style.backgroundImage = `url('images/${anime}.jpg')`;

// this script will place the anime id as the background of the post
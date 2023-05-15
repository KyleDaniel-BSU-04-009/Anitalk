var latestBtn = document.querySelector('label[for="latest"]');
var oldestBtn = document.querySelector('label[for="oldest"]');
var feed = document.querySelector('.feedCollection');

latestBtn.click();
latestBtn.addEventListener("click", event => {
  feed.style.flexDirection = 'column-reverse';
});
oldestBtn.addEventListener("click", event => {
  feed.style.flexDirection = 'column';
});

// this is a script that lets the user sort between the newest and oldest comments
// but really, the trick here was just using flexbox's direction :D
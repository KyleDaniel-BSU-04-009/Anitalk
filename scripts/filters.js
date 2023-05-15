var latestBtn = document.querySelector('label[for="latest"]');
var oldestBtn = document.querySelector('label[for="oldest"]');
var feed = document.querySelector('.feedCollection');
var sisw = document.querySelector('.sidebarSwitch');
var siswicon = document.querySelector('.siswicon');
var userContent = document.querySelector('.userContent');
var postTagList = document.querySelector('.postTagList');
var filtersList = document.querySelector('.filtersList');

var tagBtn = document.querySelectorAll('.tagButtons label');
tagBtn.forEach(btn =>{
  btn.addEventListener("click", event =>{
    // This is enough spaghetti to feed a dinner party!
    // btn.textContent == 'review' ? posts.style.display = 'flex', document.querySelectorAll('.userPost:not(:has(.reviewPostTag))').forEach(post => post.style.display = 'none') : null;
    // btn.textContent == 'art' ? posts.style.display = 'flex', document.querySelectorAll('.userPost:not(:has(.artPostTag))').forEach(post => post.style.display = 'none') : null;
    // btn.textContent == 'criticism' ? posts.style.display = 'flex', document.querySelectorAll('.userPost:not(:has(.criticismPostTag))').forEach(post => post.style.display = 'none') : null;
    // btn.textContent == 'rant' ? posts.style.display = 'flex', document.querySelectorAll('.userPost:not(:has(.rantPostTag))').forEach(post => post.style.display = 'none') : null;
    // btn.textContent == 'humor' ? posts.style.display = 'flex', document.querySelectorAll('.userPost:not(:has(.humorPostTag))').forEach(post => post.style.display = 'none') : null;
    var posts = document.querySelectorAll('.userPost');
    posts.forEach(post => post.style.display = 'flex');
    document.querySelectorAll(`.userPost:not(:has(.${btn.textContent}PostTag))`).forEach(post => post.style.display = 'none');
    
    // whenever the user presses a tag, this function will select all the tags
    // except the one that was pressed and hide posts that contain these tags
  });
});

latestBtn.click();
latestBtn.addEventListener("click", event => {
  feed.style.flexDirection = 'column-reverse';
});
oldestBtn.addEventListener("click", event => {
  feed.style.flexDirection = 'column';
});
// These group of functions allow the user to filter posts based on how old or new they are
// but really, this is just making use of flexbox's flex direction

var siswStatus = 0;
sisw.addEventListener("click", event => {
  if(siswStatus == 0){
    siswStatus++;
    userContent.style.display = 'none';
    postTagList.style.display = 'block';
    filtersList.style.display = 'block';
    siswicon.style.clipPath = 'polygon(10% 100%, 10% 80%, 30% 70%, 20% 50%, 20% 25%, 35% 0, 65% 0, 80% 25%, 80% 50%, 70% 70%, 90% 80%, 90% 100%)';
  }
  else{
    siswStatus--;
    userContent.style.display = 'flex';
    postTagList.style.display = 'none';
    filtersList.style.display = 'none';
    siswicon.style.clipPath = 'polygon(0 0, 100% 0, 60% 50%, 60% 100%, 40% 90%, 40% 50%)';
  }
});
// this is a function that will switch between pages in the left bar
// switching between user information and filter settings

var feedCollection = document.querySelector('.feedCollection');
var mostLikes = document.querySelector('label[for="mostLikes"]');
var mostComments = document.querySelector('label[for="mostComments"]');
var leastLikes = document.querySelector('label[for="leastLikes"]');
var leastComments = document.querySelector('label[for="leastComments"]');

function comparatorGlikes(a, b) {// this is a function that will determine the order of array's contents as ascending
  if (a.dataset.likecount < b.dataset.likecount)// a comparison being made will result in a value being returned
    return -1;
  if (a.dataset.likecount > b.dataset.likecount)
    return 1;
  return 0;
}
function comparatorLlikes(a, b) {
  if (a.dataset.likecount < b.dataset.likecount)
    return 1;
  if (a.dataset.likecount > b.dataset.likecount)
    return -1;
  return 0;
}
function comparatorGcomments(a, b) {
  if (a.dataset.commcount < b.dataset.commcount)
    return -1;
  if (a.dataset.commcount > b.dataset.commcount)
    return 1;
  return 0;
}
function comparatorLcomments(a, b) {
  if (a.dataset.commcount < b.dataset.commcount)
    return 1;
  if (a.dataset.commcount > b.dataset.commcount)
    return -1;
  return 0;
}
// these are functions used to sort using the parameters given to them

mostLikes.addEventListener("click", event => {
  var likecountes = document.querySelectorAll('[data-likecount]');// selects the data attribute of the post as a node list
  var likecountesArray = Array.from(likecountes);// creates an array from the node list
  let sorted = likecountesArray.sort(comparatorGlikes);// sorts the contents of the array by passing its values to a sorting function
  sorted.forEach(e => feedCollection.appendChild(e));// displays the contents of the sorted array in the feed collection
});
leastLikes.addEventListener("click", event => {
  var likecountes = document.querySelectorAll('[data-likecount]');
  var likecountesArray = Array.from(likecountes);
  let sorted = likecountesArray.sort(comparatorLlikes);
  sorted.forEach(e => feedCollection.appendChild(e));
});
mostComments.addEventListener("click", event => {
  var commcountes = document.querySelectorAll('[data-commcount]');
  var commcountesArray = Array.from(commcountes);
  let sorted = commcountesArray.sort(comparatorGcomments);
  sorted.forEach(e => feedCollection.appendChild(e));
});
leastComments.addEventListener("click", event => {
  var commcountes = document.querySelectorAll('[data-commcount]');
  var commcountesArray = Array.from(commcountes);
  let sorted = commcountesArray.sort(comparatorLcomments);
  sorted.forEach(e => feedCollection.appendChild(e));
});
// these are a set of functions that will arrange the posts based on the number of their likes or comments

document.querySelector('.resetFilters').addEventListener("click", event => {
  location.reload();
});
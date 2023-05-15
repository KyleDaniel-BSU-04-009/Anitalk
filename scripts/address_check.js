// this is used to parse the array as json in order to be checked if the content exists
// this is a way to catch an error and not leave an awkward page without content
var list = JSON.parse(JSON.stringify(animeList));

// this will cycle through the entire array to check if the id macthes a single time
var check = 0;
for(var i = 0; i < list.length; i++){
  if(anime == list[i].id){ check++ };
}

// if no match is found or the address is wrong, the page will display this message
if(check == 0){
  document.querySelector('.postForm').remove();
  document.querySelector('.postsBanner p').textContent = "This content doesn't exist, sorry!";
}
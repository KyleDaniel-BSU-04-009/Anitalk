// this is a script used to append children to the emplibrary
// this is an efficient way to add content by updating the json array instead of manually
// adding divs inside the html file
var list = JSON.parse(JSON.stringify(animeList));
const library = document.querySelector('content.animeLibrary');

// this is a loop that will cycle through the entire array that was parsed as a json array
for(var i = 0; i < list.length; i++){
  var newCell = document.createElement('div');
  newCell.classList.add('animeCell');
  newCell.style.backgroundImage = `url('images/${list[i].id}.jpg')`;
  library.appendChild(newCell);
  
  var cells = document.getElementsByClassName('animeCell');
  var newDiv = document.createElement('div');
  cells[i].appendChild(newDiv);
  
  var divs = cells[i].querySelector('div');
  
  var newP = document.createElement('p');
  newP.classList.add('animeTitle');
  newP.textContent = list[i].name;
  divs.appendChild(newP);
  
  var newEnter = document.createElement('a');
  newEnter.classList.add('enterLink');
  newEnter.href = `posts.php?anime=${list[i].id}`;
  divs.appendChild(newEnter);
  
  var newSpan = document.createElement('span');
  newSpan.classList.add('postLink');
  newSpan.id = list[i].id;
  divs.appendChild(newSpan);
  
  // each new element being appended is a cell inside the library
}

// this will allow the user to enter a link based on the content they clicked on
const postLink = document.querySelectorAll('span.postLink');

postLink.forEach(lnk => {
  // the address will be based on the id of the cell
  lnk.addEventListener("click", event =>{
    window.location.href = `compose.php?anime=${lnk.id}`;
  });
});
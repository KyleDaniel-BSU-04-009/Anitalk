var posts = document.querySelectorAll('div.rmv');
var modal = document.querySelector('div.deleteModal');
var editM = document.querySelector('div.editModal');
var edittext = document.getElementById('edittext');
var idofcontent = document.getElementById('idofcontent');
const delButtons = document.querySelectorAll('div.deleteModal div.modalButtons button');
const ediButtons = document.querySelectorAll('div.editModal div.modalButtons button');

// Opens the first modal
posts.forEach(cell => {
  cell.addEventListener("click", event =>{
    event.stopPropagation();
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    delButtons[0].classList.add(cell.id);// will provide the id of the content to each button
    delButtons[1].classList.add(cell.id);
    ediButtons[1].classList.add(cell.id);
  });
});

// button functions of the first modal
delButtons.forEach(btn => {
  btn.addEventListener("click", event =>{
    if(btn.id == 'cancel'){ closeModal(); }// pressing cancel calls a function that closes the modal
    else if(btn.id == 'edit'){ editModal(btn.className); }// pressing edit calls a function that opens the second modal
    else{ deletePost(btn.className); }// pressing delete calls a function to delete the content
  });
});

// button functions of the seconds modal
ediButtons.forEach(btn => {
  btn.addEventListener("click", event =>{
    if(btn.id == 'cancel'){ closeModal(); }// pressing cancel calls a function that closes the modal
  });
});

// closes all the modals
function closeModal(){
  modal.style.display = 'none';
  editM.style.display = 'none';
  document.body.style.overflow = 'auto';
  delButtons[0].className = '';
  ediButtons[1].className = '';
  edittext.value = '';
  idofcontent.value = '';
  
  // this is a function that will close the modal and removes the content id from the button's attributes
}

// opens the second modal
function editModal(cls){
  modal.style.display = 'none';
  editM.style.display = 'flex';
  document.body.style.overflow = 'hidden';
  idofcontent.value = cls;
  
  // this will open a modal that will allow the user to edit their content and add the id of the content as well
}

// transports the user to a different file to delete the content
function deletePost(cls){
  window.location.href = `delete.php?content=${cls}`;
}
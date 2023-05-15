var accDel = document.getElementById('accDel');
var deleteForm = document.getElementById('deleteForm');
var passwordReset = document.getElementById('passwordReset');
var passwordForm = document.getElementById('passwordForm');

accDel.addEventListener("click", event =>{
  deleteForm.style.display = 'flex';
  accDel.style.display = 'none';
});
passwordReset.addEventListener("click", event =>{
  passwordForm.style.display = 'flex';
  passwordReset.style.display = 'none';
});

// this is a script that will display the extra options when pressing
// the account delete button and password reset button
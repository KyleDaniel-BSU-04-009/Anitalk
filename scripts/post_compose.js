document.querySelector('.postPanel').addEventListener("click", event => {
  window.location.href = `compose.php?anime=${anime}`;
});

// this is a script that will allow the user to create a post based
// on the anime they're on and redirect them to the compose page
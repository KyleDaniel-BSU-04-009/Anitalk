var buttons = document.querySelectorAll('div.userContent div h3:not(:has(strong))');

buttons.forEach(btn => {
  btn.addEventListener("click", event => {
    if(btn.textContent.match('Posts')){
      window.location.href = 'user.php';
    }
    if(btn.textContent.match('Comments')){
      window.location.href = 'comments.php';
    }
    if(btn.textContent.match('Likes')){
      window.location.href = 'likes.php';
    }
  });
});

// this is a script that allows the user to navigate through
// the user's information
// whenever an h3 element is clicked excluding ones that contain the strong tag
// will redirect the user to that page
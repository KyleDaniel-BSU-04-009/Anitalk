var userposts = document.querySelectorAll('div.enterThisPost');
userposts.forEach(post => {
  post.addEventListener("click", event => {
    window.location.href = `userpost.php?post=${post.id}`;
  });
});

// this is a script that allows the user to enter posts as a pseudo link
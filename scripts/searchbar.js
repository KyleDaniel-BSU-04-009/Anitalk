const searchBar = document.getElementById('animeSearch');

searchBar.addEventListener('input', event => {
  let mainValue;
  var contentLink = document.querySelectorAll('.animeCell');
  let filter = searchBar.value.toUpperCase();
  for(var i=0; i < contentLink.length; i++){
  console.log('here');
    mainValue = contentLink[i].textValue || contentLink[i].innerText;
    if(mainValue.toUpperCase().indexOf(filter) > -1)
    { contentLink[i].style.display=''; }
    else
    { contentLink[i].style.display='none'; }
  }
});

// this is a 
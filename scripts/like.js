const urlParams = new URLSearchParams(window.location.search);
const anime = urlParams.get('anime');

// this is AJAX code that sends the id of the post and add likes using jQuery
$('.likeTrigger').on('click', function(){
  var data = {
    likedpost: $(this).attr('id')
  };
  $.ajax({
    type: 'post',
    data: data
  })
})

// this function will allow the post to increment likes live on screen
// only if the post is not made by the same user
var heartCount = document.querySelectorAll('.likeTrigger');
heartCount.forEach(like => {
  like.addEventListener("click", event => {
    event.stopPropagation();
    if(!like.classList.contains('currUser')){// checks if the post contains a class if it's made by the same user
      var icon = like.querySelector('.postIcon');
      var count = like.querySelector('span.contentCount');
      if(icon.classList.contains('notLiked')){// if the post has not been liked, it will increment its count
        icon.classList.remove('notLiked');
        icon.classList.add('isLiked');
        count.textContent++;
      }
      else{// if the post has been liked already, it will decrement its count
        icon.classList.remove('isLiked');
        icon.classList.add('notLiked');
        count.textContent--;
      }
    }
  });
});
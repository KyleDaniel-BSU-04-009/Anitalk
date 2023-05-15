var params = new URLSearchParams(window.location.search);
var postid = params.get('post');

var centerContent = document.querySelector('.centerContent');
var commentSection = document.querySelector('.commentSection');

// these group of if-statements will check the width of the window
// if its lesss than 1500px and place the comment section below the post
if(window.innerWidth < 1500){
  commentSection.style.top = `${centerContent.clientHeight}px`;
}
window.addEventListener("resize", event => {
  if(window.innerWidth < 1500){
    commentSection.style.top = `${centerContent.clientHeight}px`;
  }
});

// this is AJAX code that sends the id of the post and add comments using jQuery
$('.commentTrigger').on('click', function(){
  var data = {
    commenton: postid
  };
  $.ajax({
    type: 'post',
    data: data
  })
})
var remove = document.getElementById('rmvBtn');
var preview = document.getElementById('preview');
var input = document.getElementById('image');
var label = document.getElementById('inpLabel');

input.onchange = function() {
  if(this.files[0].size > 10485760){
    alert('Nee, the file is too big!');
    this.value = '';
  }
  else{
    var reader = new FileReader();
    reader.onload = function(e) {
      preview.setAttribute('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
    remove.style.display = 'block';
    preview.style.display = 'block';
    label.style.display = 'none';
  }
}
// this function will be called whenever the input for images changes values
// adding the image file as a base64 encoded string as the source for the preview image
// and allowing it to be displayed
// this function will also limit the image file size to 10mb
// so it won't accept it if it's greater than that

function removeImage(){
  preview.style.display = 'none';
  remove.style.display = 'none';
  label.style.display = 'block';
  preview.src = '';
  input.type = 'file';
  input.value = null;
}
// this functoin will remove the preview image and remove
// the valuen of the image input
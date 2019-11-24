// Function to copy contents
function copyStringToClipboard(str) {
    // Create new element
    var el = document.createElement('textarea');
    // Set value (string to be copied)
    el.value = str;
    // Set non-editable to avoid focus and move outside of view
    el.setAttribute('readonly', '');
    el.style = {position: 'absolute', left: '-9999px'};
    document.body.appendChild(el);
    // Select text inside element
    el.select();
    // Copy text to clipboard
    document.execCommand('copy');
    document.body.removeChild(el);
 }
 

  let snackBarTimeout;
  
  function showSnackBar(text, color="#17fc2e", background="#212531") {
    const snackbar = document.querySelector('#snackbar');
    snackbar.textContent = text;
    snackbar.style.color = color;
    snackbar.style.backgroundColor = background;
    snackbar.classList.add('show');
    clearTimeout(snackBarTimeout);
    snackBarTimeout = setTimeout(() => {
      snackbar.classList.remove('show');
    }, 1500);
  }
 
 function openNav() {
     //if (screen.width <= 720) {
         document.getElementById("navbar").style.width = "100%";
     /*} else {
         document.getElementById("navbar").style.width = "250px";
     }*/
 }
 
 function closeNav() {
     document.getElementById("navbar").style.width = "0px";
 }
 
 $(document).ready(function(){
    let s = document.createElement('div');
    s.id = 'snackbar';
    s.textContent = 'done';
    document.body.append(s);
 });
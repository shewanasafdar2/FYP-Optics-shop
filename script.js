
// Get the button element
let btn = document.getElementById('back-to-top-btn');

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    btn.style.display = 'block';
  } else {
    btn.style.display = 'none';
  }
};

// Smooth scroll to the top of the document when the button is clicked
btn.onclick = function() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
};


function myFunction() {
  var x = document.getElementById("navLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

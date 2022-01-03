 // Load the page for header and footer using jQuery
 $("#owner_header").load("owner_header.php");
 $("#header").load("header.php");
 $("#footer").load("footer.html");


 // Change background color for sticky header when scrolling
 window.onscroll = function () {
     stickyHeader();
 };

 function stickyHeader() {
     var navSection = document.getElementById("navbar-section");
     // Get the offset position of the navbar
     var stickyPos = navSection.offsetTop;
     if (window.pageYOffset > stickyPos) {
         navSection.classList.add("navbar-custom");
     } else {
         // If the triple bar is not clicked, then the background color should be removed
         if (!document.getElementById("navbarSupportedContent").classList.contains("show")) {
             navSection.classList.remove("navbar-custom");
         }
     }
 }


 // make successfully updated message disappear after 5 seconds
 setTimeout(fade_out, 5000);
 function fade_out() {
    
     $("#success_msg").fadeOut().empty();
  }

  //onclick plus button and redirect to add owner space's page
  function addSpace(){
    window.location = "owner_signIn.php";
  }

  //onclick sign out button and redirect to home page
  function signOut_redirect(){
    window.location = "signOut.php";
  }


   //to delete customer's favourite space by clicking delete button
 function deleteFav($favouriteID){
    if (confirm("Are you sure you want to delete this favourite space? This action cannot be undone.")) {
        window.location.href='account_function/customer_deleteFavSpace.php?favouriteID='+$favouriteID+'';
      
      return true;
      }
  
    }
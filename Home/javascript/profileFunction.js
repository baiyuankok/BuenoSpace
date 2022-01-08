// Load the page for header and footer using jQuery
$("#header").load("header.php");
$("#footer").load("footer.html");

// make successfully updated message disappear after 5 seconds
setTimeout(fade_out, 5000);
function fade_out() {
    $("#success_msg").fadeOut().empty();
}

setTimeout(showQueryResult, 5000);
function showQueryResult() {
    $("#query-result-msg").fadeOut().empty();
}

// onclick plus button and redirect to add owner space's page
function addSpace() {
    window.location = "../Spaces/space_listing.php";
}

// to delete customer's favourite space by clicking delete button
function deleteFav($favouriteID) {
    if (confirm("Are you sure you want to delete this favourite space? This action cannot be undone.")) {
        window.location.href = 'account_function/customer_deleteFavSpace.php?favouriteID=' + $favouriteID + '';

        return true;
    }
}
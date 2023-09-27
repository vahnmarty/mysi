document.addEventListener('DOMContentLoaded', function () {
    if(isUri('admin/applications')){
        return changeSearchPlaceholder('First Name, Last Name, Email or Phone');
    }

    if(isUri('admin/children')){
        return changeSearchPlaceholder('First Name, Last Name, School, Email or Phone');
    }

    if(isUri('admin/users')){
        return changeSearchPlaceholder('First Name, Last Name or Email');
    }
  });


function isUri($uri)
{
    var currentURL = window.location.href;
  
    // Define the URI you want to check
    var targetURI = $uri;
  
    // Check if the current URL contains the target URI
    return currentURL.indexOf(targetURI) !== -1;

}

function changeSearchPlaceholder($placeholder)
{
    var searchInput = document.querySelector('.filament-tables-search-input');

    // Check if the element exists
    if (searchInput) {
    // Find the <input> element within the searchInput element
    var inputElement = searchInput.querySelector('input');

    // Check if the <input> element exists
    if (inputElement) {
        // Change the placeholder attribute of the <input> element
        inputElement.placeholder = $placeholder;
    }
    }
}
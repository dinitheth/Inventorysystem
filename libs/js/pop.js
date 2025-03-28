// Function to display the restricted access popup
function restrictAccess(section) {
    // Display the section name in the popup
    document.getElementById('sectionName').textContent = section;
    document.getElementById('restrictedPopup').style.display = 'flex';

    // Store the section for use after authentication
    document.getElementById('restrictedPopup').setAttribute('data-section', section);
}

// Add event listener for the submit button
document.getElementById('submitPassword').addEventListener('click', function() {
    const password = document.getElementById('adminPassword').value;
    const section = document.getElementById('restrictedPopup').getAttribute('data-section');
    
    if (password === '123') {
        alert('Access granted!');
        closePopup();
        
        // Redirect based on the section the user is trying to access
        redirectToPage(section);
    } else {
        alert('Incorrect password');
    }
});

// Function to close the popup
document.getElementById('cancelPopup').addEventListener('click', closePopup);
function closePopup() {
    document.getElementById('restrictedPopup').style.display = 'none';
}

// Function to handle redirection based on section
function redirectToPage(section) {
    switch (section) {
        case 'Sales Report':
            window.location.href = "sales_report.php";
            break;
        case 'Monthly Sales':
            window.location.href = "monthly_sales.php";
            break;
        case 'Daily Sales':
            window.location.href = "daily_sales.php";
            break;
        case 'Users':
            window.location.href = "users.php";
            break;
        default:
            alert('Unknown section');
            break;
    }
}

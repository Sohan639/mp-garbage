fetch(`${config_url.frontendBaseURL}/menu.html`)
.then(response => response.text())
.then(data => {
document.getElementById('horizontal-menu-placeholder').innerHTML = data;

const profileName = localStorage.getItem('name');
const profileRole = localStorage.getItem('role');
const profileImage = localStorage.getItem('profile');
const navProfileImage = document.getElementById('nav-profile-image');

document.getElementById('nav-profile-name').textContent = profileName;
document.getElementById('nav-profile-role').textContent = profileRole;
if (!profileImage) {
    // If profileImage is empty or null
    navProfileImage.style.backgroundImage = "url('./static/profile.jpg')";
} else {
    // If profileImage is not empty
    navProfileImage.style.backgroundImage = `url('${profileImage}')`;
}

const currentPage = window.location.pathname.split('/').pop(); 
const urlParams = new URLSearchParams(window.location.search);
const location = urlParams.get('location');

const role = localStorage.getItem('role');
const houseSurveyNav = document.getElementById('housesurvey-nav');
const usersNav = document.getElementById('users-nav');
const changePasswordNav = document.getElementById('change-password-nav');
const dashboardNav = document.getElementById('dashboard-nav');
const logsNav = document.getElementById('logs-nav');
const garbageNav = document.getElementById('garbage-nav');
const garbageAdminNav = document.getElementById('garbage-admin-nav');
const dashboardLink = dashboardNav.querySelector('a');
if(role == "admin"){
    houseSurveyNav.classList.remove('d-none');
    usersNav.classList.remove('d-none');
    changePasswordNav.classList.remove('d-none');
    garbageAdminNav.classList.remove('d-none');
    logsNav.classList.add('d-none'); // hide logs menu
    garbageNav.classList.add('d-none'); // hide garbage menu
    dashboardLink.href = 'dashboard.html';
} else if (role == "citizen"){
    houseSurveyNav.classList.add('d-none');
    usersNav.classList.add('d-none');
    changePasswordNav.classList.add('d-none');
    garbageAdminNav.classList.add('d-none');
    logsNav.classList.add('d-none'); // hide logs menu
    garbageNav.classList.add('d-none'); // hide garbage menu
    dashboardLink.href = 'resident.html';
}
else if (role == "officer"){
    dashboardNav.classList.add('d-none');
    usersNav.classList.add('d-none');
    changePasswordNav.classList.add('d-none');
    garbageAdminNav.classList.add('d-none');
    logsNav.classList.add('d-none'); // hide logs menu
    garbageNav.classList.add('d-none'); // hide garbage menu
    dashboardLink.href = 'housesurvey.html';
} else if (role == "superadmin"){
    houseSurveyNav.classList.remove('d-none'); // show house survey menu
    usersNav.classList.remove('d-none'); // show user menu
    changePasswordNav.classList.remove('d-none'); // show change password menu
    garbageAdminNav.classList.remove('d-none');
    logsNav.classList.remove('d-none'); // show logs menu
    garbageNav.classList.remove('d-none'); // show garbage menu
    dashboardLink.href = 'dashboard.html'; // show dashboard as starting page
} else if (role == "cleaning-staff"){
    houseSurveyNav.classList.add('d-none'); // show house survey menu
    usersNav.classList.add('d-none'); // show user menu
    changePasswordNav.classList.add('d-none'); // show change password menu
    garbageAdminNav.classList.add('d-none');
    logsNav.classList.add('d-none'); // show logs menu
    dashboardNav.classList.add('d-none'); // show logs menu
    garbageNav.classList.remove('d-none'); // show garbage menu
}

if (currentPage === 'dashboard.html') {
    document.getElementById('dashboard-nav').classList.add('active');
    document.getElementById('housesurvey-nav').classList.remove('active');
    document.getElementById('resident-detail-nav').classList.remove('active');
    document.getElementById('resident-nav').classList.remove('active');
    document.getElementById('users-nav').classList.remove('active');
    document.getElementById('change-password-nav').classList.remove('active');
    document.getElementById('logs-nav').classList.remove('active');
    document.getElementById('garbage-admin-nav').classList.remove('active');
} else if (currentPage === 'housesurvey.html' || currentPage === 'addhousesurvey.html' || currentPage === 'updatehousesurvey.html'|| (currentPage === 'viewhousesurvey.html' && location === 'housesurvey')){
    document.getElementById('dashboard-nav').classList.remove('active');
    document.getElementById('housesurvey-nav').classList.add('active');
    document.getElementById('resident-detail-nav').classList.remove('active');
    document.getElementById('resident-nav').classList.remove('active');
    document.getElementById('users-nav').classList.remove('active');
    document.getElementById('change-password-nav').classList.remove('active');
    document.getElementById('logs-nav').classList.remove('active');
    document.getElementById('garbage-admin-nav').classList.remove('active');
}else if (currentPage === 'residentList.html' || (currentPage === 'viewhousesurvey.html' && location === 'resident')){
    document.getElementById('dashboard-nav').classList.remove('active');
    document.getElementById('housesurvey-nav').classList.remove('active');
    document.getElementById('resident-detail-nav').classList.remove('active');
    document.getElementById('resident-nav').classList.add('active');
    document.getElementById('users-nav').classList.remove('active');
    document.getElementById('change-password-nav').classList.remove('active');
    document.getElementById('logs-nav').classList.remove('active');
    document.getElementById('garbage-admin-nav').classList.remove('active');
}else if (currentPage === 'resident.html'){
    document.getElementById('dashboard-nav').classList.remove('active');
    document.getElementById('housesurvey-nav').classList.remove('active');
    document.getElementById('resident-nav').classList.remove('active');
    document.getElementById('resident-detail-nav').classList.add('active');
    document.getElementById('users-nav').classList.remove('active');
    document.getElementById('change-password-nav').classList.remove('active');
    document.getElementById('logs-nav').classList.remove('active');
    document.getElementById('garbage-admin-nav').classList.remove('active');
}else if (currentPage === 'users.html' || currentPage === 'adduser.html' || currentPage === 'updateuser.html' || currentPage === 'viewuser.html'){
    document.getElementById('dashboard-nav').classList.remove('active');
    document.getElementById('housesurvey-nav').classList.remove('active');
    document.getElementById('resident-nav').classList.remove('active');
    document.getElementById('resident-detail-nav').classList.remove('active');
    document.getElementById('change-password-nav').classList.remove('active');
    document.getElementById('users-nav').classList.add('active');
    document.getElementById('logs-nav').classList.remove('active');
    document.getElementById('garbage-admin-nav').classList.remove('active');
} else if (currentPage === 'changepassword.html'){
    document.getElementById('dashboard-nav').classList.remove('active');
    document.getElementById('housesurvey-nav').classList.remove('active');
    document.getElementById('resident-nav').classList.remove('active');
    document.getElementById('resident-detail-nav').classList.remove('active');
    document.getElementById('users-nav').classList.remove('active');
    document.getElementById('change-password-nav').classList.add('active');
    document.getElementById('logs-nav').classList.remove('active');
    document.getElementById('garbage-admin-nav').classList.remove('active');
} else if (currentPage === 'logs.html'){
    document.getElementById('dashboard-nav').classList.remove('active');
    document.getElementById('housesurvey-nav').classList.remove('active');
    document.getElementById('resident-detail-nav').classList.remove('active');
    document.getElementById('resident-nav').classList.remove('active');
    document.getElementById('users-nav').classList.remove('active');
    document.getElementById('change-password-nav').classList.remove('active');
    document.getElementById('garbage-nav').classList.remove('active');
    document.getElementById('logs-nav').classList.add('active');
    document.getElementById('garbage-admin-nav').classList.remove('active');
} else if (currentPage == "garbagecollection.html"){
    document.getElementById('garbage-nav').classList.add('active');
} else if (currentPage == "garbage.html"){
    document.getElementById('dashboard-nav').classList.remove('active');
    document.getElementById('housesurvey-nav').classList.remove('active');
    document.getElementById('resident-detail-nav').classList.remove('active');
    document.getElementById('resident-nav').classList.remove('active');
    document.getElementById('users-nav').classList.remove('active');
    document.getElementById('change-password-nav').classList.remove('active');
    document.getElementById('garbage-nav').classList.remove('active');
    document.getElementById('logs-nav').classList.remove('active');
    document.getElementById('garbage-admin-nav').classList.add('active');
}

if(role === 'admin')
{
    // document.getElementById('dashboard-nav').classList.add('active');
    document.getElementById('housesurvey-nav').classList.remove('d-none');
    document.getElementById('resident-nav').classList.remove('d-none');
    document.getElementById('resident-detail-nav').classList.add('d-none');
}
if(role === 'officer')
    {
        document.getElementById('dashboard-nav').classList.add('d-none');
        // document.getElementById('housesurvey-nav').classList.add('active');
        document.getElementById('resident-nav').classList.remove('d-none');
        document.getElementById('resident-detail-nav').classList.remove('d-none');
    }
if(role === 'citizen')
    {
        document.getElementById('dashboard-nav').classList.add('d-none');
        document.getElementById('housesurvey-nav').classList.add('d-none');
        document.getElementById('resident-nav').classList.add('d-none');
        document.getElementById('resident-detail-nav').classList.remove('d-none');
        // document.getElementById('resident-detail-nav').classList.add('active');
    }
if(role === 'superadmin')
    {
        // document.getElementById('dashboard-nav').classList.add('active');
        document.getElementById('housesurvey-nav').classList.remove('d-none');
        document.getElementById('resident-nav').classList.remove('d-none');
        document.getElementById('resident-detail-nav').classList.add('d-none');
    } else if (role === 'cleaning-staff'){
        document.getElementById('resident-nav').classList.add('d-none');
        document.getElementById('resident-detail-nav').classList.add('d-none');
    }
})
.catch(error => console.error('Error loading the horizontal menu:', error));

fetch(`${config_url.frontendBaseURL}/footer.html`)
.then(response => response.text())
.then(data => {
document.getElementById('footer-placeholder').innerHTML = data;
// document.getElementById('terms-and-conditions').href=`${config_url.frontendBaseURL}/terms-and-conditions.pdf`;
// document.getElementById('privacy-policy').href=`${config_url.frontendBaseURL}/privacy-policy.pdf`;

})
.catch(error => console.error('Error loading the footer:', error));

async function logoutPage() {
    const data = {
        userId: localStorage.getItem('userId')
    } 
    try {
        const logoutResponse = await logout(url = `logout`, data);
        if (logoutResponse) {
    
            localStorage.clear();
            sessionStorage.clear();
    
            window.location = config_url.frontendBaseURL;
            
            // Prevent going back after logout
            setTimeout(() => {
                window.history.pushState(null, '', config_url.frontendBaseURL);
                window.history.replaceState(null, '', config_url.frontendBaseURL);
            }, 0);
    
        }
    } catch (error) {
        console.error(error)
    }

}
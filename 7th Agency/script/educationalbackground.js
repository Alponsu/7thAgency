const maxSchoolDetails = 3;

document.getElementById('next-button').onclick = function() {
    window.location.href = 'workexperience.php';
};

document.getElementById('previous-button').onclick = function() {
    window.location.href = 'personalinformation2.php';
};

function addSchoolDetails() {
    const container = document.getElementById('schoolDetailsContainer');
    const schoolDetailsCount = container.getElementsByClassName('school-details').length;

    if (schoolDetailsCount < maxSchoolDetails) {
        const newIndex = schoolDetailsCount + 1;
        const schoolDetails = document.createElement('div');
        schoolDetails.className = 'school-details';
        schoolDetails.innerHTML = `
            <div class="form-group">
                <label for="school-name-${newIndex}">SCHOOL NAME</label>
                <input type="text" id="school-name-${newIndex}" name="school-name" placeholder="SCHOOL NAME">
            </div>
            <div class="form-group">
                <label for="location-${newIndex}">LOCATION</label>
                <input type="text" id="location-${newIndex}" name="location" placeholder="LOCATION">
            </div>
            <div class="form-group">
                <label for="years-attended-${newIndex}">YEARS ATTENDED</label>
                <input type="text" id="years-attended-${newIndex}" name="years-attended" placeholder="YEARS ATTENDED">
            </div>
            <div class="form-group">
                <label for="degree-received-${newIndex}">DEGREE RECEIVED</label>
                <input type="text" id="degree-received-${newIndex}" name="degree-received" placeholder="DEGREE RECEIVED">
            </div>
            <div class="form-group">
                <label for="major-${newIndex}">MAJOR</label>
                <input type="text" id="major-${newIndex}" name="major" placeholder="MAJOR">
            </div>
        `;
        container.appendChild(schoolDetails);
    } else {
        alert('You can add a maximum of 3 schools.');
    }
}

function removeSchoolDetails() {
    const container = document.getElementById('schoolDetailsContainer');
    const schoolDetailsCount = container.getElementsByClassName('school-details').length;

    if (schoolDetailsCount > 1) {
        container.removeChild(container.lastElementChild);
    } else {
        alert('You must have at least one school detail.');
    }
}

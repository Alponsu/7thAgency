const maxSchoolDetails = 3;

function addSchoolDetails() {
    const container = document.getElementById('schoolDetailsContainer');
    const schoolDetailsCount = container.getElementsByClassName('school-details').length;

    if (schoolDetailsCount < maxSchoolDetails) {
        const newIndex = schoolDetailsCount + 1;
        const schoolDetails = document.createElement('div');
        schoolDetails.className = 'az-div school-details';
        schoolDetails.innerHTML = `
            <div class="form-group">
                <label for="school-name-${newIndex}">SCHOOL NAME</label>
                <input type="text" id="school-name-${newIndex}" name="school-name[]" autocomplete="off" oninput="searchSchool(this)">
                <div id="schoolDropdown-${newIndex}" class="dropdown"></div>
            </div>
            <div class="form-group">
                <label for="location-${newIndex}">LOCATION</label>
                <input type="text" id="location-${newIndex}" name="location[]">
            </div>
            <div class="form-group">
                <label for="years-attended-${newIndex}">YEARS ATTENDED</label>
                <input type="text" id="years-attended-${newIndex}" name="years-attended[]">
            </div>
            <div class="form-group">
                <label for="degree-received-${newIndex}">DEGREE RECEIVED</label>
                <input type="text" id="degree-received-${newIndex}" name="degree-received[]">
            </div>
            <div class="form-group">
                <label for="major-${newIndex}">MAJOR</label>
                <input type="text" id="major-${newIndex}" name="major[]">
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

function searchSchool(inputElement) {
    const query = inputElement.value;
    const dropdown = inputElement.nextElementSibling; 


    if (query.trim() === '') {
        dropdown.style.display = 'none';
        return;
    }

    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'search.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            dropdown.innerHTML = xhr.responseText;
            dropdown.style.display = 'block';

            const items = dropdown.getElementsByClassName('dropdown-item');
            for (let item of items) {
                item.addEventListener('click', function () {
                    inputElement.value = item.getAttribute('data-schoolname');
                    const locationInput = inputElement.closest('.school-details').querySelector('input[name="location[]"]');
                    locationInput.value = item.getAttribute('data-location');
                    dropdown.style.display = 'none';
                });
            }
        }
    };
    xhr.send('query=' + encodeURIComponent(query));
}

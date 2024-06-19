const maxCharacterDetails = 3;

document.getElementById('next-button').onclick = function() {
    window.location.href = 'thankyou.php';
};

document.getElementById('previous-button').onclick = function() {
    window.location.href = 'workexperience.php';
};

function addCharacter() {
    const container = document.getElementById('characterDetailsContainer');
    const characterDetailsCount = container.getElementsByClassName('character-details').length;

    if (characterDetailsCount < maxCharacterDetails) {
        const newIndex = characterDetailsCount + 1;
        const characterDetails = document.createElement('div');
        characterDetails.className = 'character-details';
        characterDetails.innerHTML = `
            <div class="form-group">
                <label for="character-name-${newIndex}">NAME</label>
                <input type="text" id="character-name-${newIndex}" name="character-name" placeholder="NAME">
            </div>
            <div class="form-group">
                <label for="location-${newIndex}">TITLE</label>
                <input type="text" id="location-${newIndex}" name="location" placeholder="LOCATION">
            </div>
            <div class="form-group">
                <label for="company-${newIndex}">COMPANY</label>
                <input type="text" id="company-${newIndex}" name="company" placeholder="COMPANY">
            </div>
            <div class="form-group">
                <label for="phone-${newIndex}">PHONE</label>
                <input type="text" id="phone-${newIndex}" name="phone" placeholder="PHONE">
            </div>
        `;
        container.appendChild(characterDetails);
    } else {
        alert('You can add a maximum of 3 character references.');
    }
}

function removeCharacter() {
    const container = document.getElementById('characterDetailsContainer');
    const characterDetailsCount = container.getElementsByClassName('character-details').length;

    if (characterDetailsCount > 1) {
        container.removeChild(container.lastElementChild);
    } else {
        alert('You must have at least one character reference.');
    }
}

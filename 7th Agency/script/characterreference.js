const maxCharacterDetails = 3;

function addCharacter() {
    const container = document.getElementById('characterDetailsContainer');
    const characterDetailsCount = container.getElementsByClassName('character-details').length;

    if (characterDetailsCount < maxCharacterDetails) {
        const newIndex = characterDetailsCount + 1;
        const characterDetails = document.createElement('div');
        characterDetails.className = 'az-div character-details';
        characterDetails.innerHTML = `
            <div class="form-group">
                <label for="character-name-${newIndex}">NAME</label>
                <input type="text" id="character-name-${newIndex}" name="character-name[]">
            </div>
            <div class="form-group">
                <label for="title-${newIndex}">TITLE</label>
                <input type="text" id="title-${newIndex}" name="title[]">
            </div>
            <div class="form-group">
                <label for="company-${newIndex}">COMPANY</label>
                <input type="text" id="company-${newIndex}" name="company[]">
            </div>
            <div class="form-group">
                <label for="phone-${newIndex}">PHONE</label>
                <input type="text" id="phone-${newIndex}" name="phone[]">
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
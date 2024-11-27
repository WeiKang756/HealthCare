document.getElementById('searchButton').addEventListener('click', function () {
    const patientICNumber = document.getElementById('patientICNumberInput').value;

    fetchPatientInfo(patientICNumber)
        .then(patientInfo => {
            updatePatientInfoUI(patientInfo);
        })
        .catch(error => {
            console.error("Error fetching patient information:", error);
            // Handle the error or display an error message
        });
});

function fetchPatientInfo(patientICNumber) {
    return fetch(`api.php?action=getPatientInfo&icNumber=${patientICNumber}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        });
}

function updatePatientInfoUI(patientInfo) {
    const patientInfoContainer = document.getElementById('patientInfoContainer');

    patientInfoContainer.innerHTML = `
        <p>Name: ${patientInfo.user_ICNumber}</p>
        <p>Age: ${patientInfo.user_name}</p>
        <!-- Add more properties as needed -->
    `;
}

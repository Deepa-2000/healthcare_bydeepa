document.addEventListener("DOMContentLoaded", function () {
    const registrationType = document.getElementById("registerationType");
    const companyNameField = document.getElementById("company_name").closest(".mb-3");
    const employeesField = document.getElementById("employees").closest(".mb-3");

    // Initially hide the fields using Bootstrap's `d-none` class
    companyNameField.classList.add("d-none");
    employeesField.classList.add("d-none");

    // Add event listener for change on Registration Type field
    registrationType.addEventListener("change", function () {
        const selectedValue = registrationType.value;

        if (selectedValue === "Company" || selectedValue === "proprietor") {
            companyNameField.classList.remove("d-none");
            employeesField.classList.remove("d-none");
        } else {
            companyNameField.classList.add("d-none");
            employeesField.classList.add("d-none");
        }
    });
});
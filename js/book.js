// Disable past dates in the start date input
document.getElementById("start_date").setAttribute("min", new Date().toISOString().split("T")[0]);

// Calculate total price dynamically
function calculateTotalPrice() {
    var roomSelect = document.getElementById("room_id");
    var roomPrice = roomSelect.options[roomSelect.selectedIndex].getAttribute("data-price");
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
    var totalPriceElement = document.getElementById("total-price");

    // Set minimum end date based on the selected start date
    if (startDate) {
        document.getElementById("end_date").setAttribute("min", startDate);
    }

    if (roomPrice && startDate && endDate) {
        var startTimestamp = new Date(startDate).getTime();
        var endTimestamp = new Date(endDate).getTime();
        var days = Math.ceil((endTimestamp - startTimestamp) / (1000 * 3600 * 24));

        if (days > 0) {
            var totalPrice = roomPrice * days;
            totalPriceElement.innerText = "Total Price: " + totalPrice + " GHS";
        } else {
            totalPriceElement.innerText = "Total Price: 0 GHS";
        }
    }
}

// Form validation before submitting
function validateForm() {
    var totalPriceText = document.getElementById("total-price").innerText;
    if (totalPriceText === "Total Price: 0 GHS") {
        alert("Please ensure the dates are valid and the total price is calculated.");
        return false;
    }
    return true;
}

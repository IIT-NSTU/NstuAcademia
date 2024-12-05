function proceedToPayment() {
    const academicFees = parseFloat(document.getElementById("academicFees").value) || 0;
    const hallFees = parseFloat(document.getElementById("hallFees").value) || 0;
    const examFees = parseFloat(document.getElementById("examFees").value) || 0;
    
    // Calculate total
    const totalAmount = academicFees + hallFees + examFees;
    document.getElementById("totalAmount").textContent = `${totalAmount} BDT`;

    // Redirect to SSLCommerz payment gateway with total amount
    window.location.href = `https://securepay.sslcommerz.com/?amount=${totalAmount}`;
}

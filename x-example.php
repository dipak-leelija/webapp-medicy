<script>
    function calculateGST(mrp, gstPercentage) {
  // Validate inputs
  if (isNaN(mrp) || isNaN(gstPercentage) || mrp < 0 || gstPercentage < 0) {
    return "Invalid input. Please enter valid numbers.";
  }

  // Calculate GST amount
  const gstAmount = (mrp * gstPercentage) / 100;

  return gstAmount;
}

// Example usage:
const mrp = 100; // Replace with the actual MRP
const gstPercentage = 28; // Replace with the actual GST percentage

const gstAmount = calculateGST(mrp, gstPercentage);
console.log(`MRP: ${mrp}`);
console.log(`GST Amount: ${gstAmount}`);
console.log(`Base Amount: ${mrp-gstAmount}`);


</script>
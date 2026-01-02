<template>
    <div class="card shadow-sm p-3">

    <h4>Bank Transfer Payment</h4>
    <p>Please upload your payment receipt.</p>

    <!-- Bank Details -->
    <div class="mb-3">
      <h6 class="fw-bold">Bank Information</h6>
      <p><strong>Bank:</strong> XYZ Bank</p>
      <p><strong>Account Name:</strong> ABC Company</p>
      <p><strong>Account Number:</strong> 123-456-789</p>
      <p><strong>Branch Code:</strong> 000</p>
    </div>

    <!-- Upload -->
    <div class="mb-3">
      <label class="form-label fw-bold">Upload Receipt</label>
      <input type="file" class="form-control" @change="onFileChange" />
    </div>

    <!-- Preview -->
    <div v-if="preview" class="mb-3">
      <p class="fw-bold">Preview:</p>
      <img :src="preview" alt="Preview" class="img-fluid rounded" style="max-height: 250px;">
    </div>

    <!-- Submit -->
    <button 
      class="btn btn-success w-100"
      :disabled="loading || !receipt"
      @click="submitReceipt"
    >
      <span v-if="loading">Uploading...</span>
      <span v-else>Submit Payment Proof</span>
    </button>

  </div>
</template>

<script>
export default {
  props: ['orderId'],
  data() {
    return {
      receipt: null,
      preview: null,
      loading: false,
    };
  },
  methods: {
    onFileChange(event) {
      const file = event.target.files[0];
      this.receipt = file;

      if (file) {
        this.preview = URL.createObjectURL(file);
      }
    },

    async submitReceipt() {
      if (!this.receipt) return;

      this.loading = true;
      const formData = new FormData();
      formData.append("receipt", this.receipt);

      try {
        await axios.post(`/api/payment/transfer/${this.orderId}`, formData, {
          headers: { "Content-Type": "multipart/form-data" }
        });

        alert("Receipt uploaded successfully!");
        window.location.href = "/order/success";

      } catch (error) {
        alert("Upload failed. Try again.");
      }

      this.loading = false;
    }
  }
};
</script>
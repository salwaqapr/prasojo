export const formatRupiah = (value) => {
  if (!value) return "";
  return "Rp " + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

export const cleanNumber = (value) => {
  return value.replace(/\D/g, "").replace(/^0+/, "");
};

export const rupiahView = (n) => "Rp " + Number(n || 0).toLocaleString("id-ID");

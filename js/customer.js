// ===============================
// عناصر الصفحة
// ===============================

const customerName = document.getElementById("customerName");
const customerAccount = document.getElementById("customerAccount");

const nextBtn = document.querySelector(".btn-next");

// ===============================
// تحميل البيانات
// ===============================

window.addEventListener("load", () => {

    const data = JSON.parse(localStorage.getItem("customerData"));

    if (!data) return;

    customerName.value = data.name || "";
    customerAccount.value = data.account || "";

});

// ===============================
// حفظ البيانات والانتقال
// ===============================

nextBtn.addEventListener("click", (e) => {

    e.preventDefault();

    const customerData = {

        name: customerName.value.trim(),

        account: customerAccount.value.trim()

    };

    localStorage.setItem(
        "customerData",
        JSON.stringify(customerData)
    );

    window.location.href = "shipment.php";

});